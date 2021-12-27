<?php
/**
 * @file plugins/generic/boriSharing/BoriSharingPlugin.inc.php
 *
 * Copyright (c) 2021 Agência Bori
 * Developed by Lepidus Tecnologia
 * Distributed under the GNU GPL v3. For full terms see LICENSE or https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @class BoriSharingPlugin
 * @ingroup plugins_generic_boriSharing
 *
 */

import('classes.workflow.EditorDecisionActionsManager');
import('lib.pkp.classes.submission.SubmissionFile');
import('lib.pkp.classes.plugins.GenericPlugin');
import('plugins.generic.boriSharing.classes.SubmissionToShareFactory');
import('plugins.generic.boriSharing.classes.MailMessageBuilder');
import('plugins.generic.boriSharing.classes.BoriMailClient');
import('plugins.generic.boriSharing.classes.BoriAPIClient');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;

define('BASE_URI', 'https://www.abori.com.br/uploads-ojs');

class BoriSharingPlugin extends GenericPlugin {

	public function register($category, $path, $mainContextId = NULL) {
		$success = parent::register($category, $path, $mainContextId);
		
		if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return true;
		if ($success && $this->getEnabled($mainContextId)) {
			$contextId = Application::get()->getRequest()->getContext()->getId();
			$termsAccepted = $this->getSetting($contextId, 'terms_accepted');
			
			if(!empty($termsAccepted)) {
				HookRegistry::register('EditorAction::recordDecision', [$this, 'shareWhenArticleApproved']);
				HookRegistry::register('Publication::publish', array($this, 'shareWhenArticlePublished'));
			}
		}
		return $success;
	}

	public function getDisplayName() {
		return __('plugins.generic.boriSharing.displayName');
	}

	public function getDescription() {
		return __('plugins.generic.boriSharing.description');
	}

	public function shareWhenArticleApproved($hookName, $params) {
		$editorDecision = $params[1];
		if($editorDecision['decision'] == SUBMISSION_EDITOR_DECISION_ACCEPT) {
			$submission = $params[0];
			$submissionFiles = $this->getSubmissionFiles($submission);
			$contextId = $submission->getJournalId(); 
			
			$boriMailClient = new BoriMailClient($submission, $editorDecision, $submissionFiles);
			$boriMailClient->sendMail();
			
			$disableAPI = $this->getSetting($contextId, 'disable_API');
			if (!$disableAPI){
				$userAuthKey = $this->getSetting($contextId, 'user_auth_key');
				$client = new Client(['base_uri' => BASE_URI]);
				
				$boriAPIClient = new BoriAPIClient($userAuthKey,$client);
				try {
					$boriAPIClient->sendSubmissionFiles($submissionFiles);
					$message = 'The file(s) has been sent';
					error_log($message);
				} catch (ClientException $e) {
					$message = $e->getResponse()->getReasonPhrase();
					error_log($message);
				} catch (ConnectException $e) {
					$message = $e->getMessage();
					error_log($message);
				} catch (ServerException $e){
					$message = $e->getResponse()->getReasonPhrase();
					error_log($message);
				}
			}

		}
		
		return false;
	}

	private function getSubmissionFiles($submission) {
		$submissionFileService = Services::get('submissionFile');
		$submissionFiles = $submissionFileService->getMany([
			'submissionIds' => [$submission->getId()],
			'fileStages' => [SUBMISSION_FILE_REVIEW_REVISION]
		]);
		
		return iterator_to_array($submissionFiles);
	}

	public function shareWhenArticlePublished($hookName, $params) {
		$publication = $params[0];
		$submission = $params[2];
		$submissionToShareFactory = new SubmissionToShareFactory();

		$journal = DAORegistry::getDAO('JournalDAO')->getById($submission->getData('contextId'));
		$datePublished = $publication->getData('datePublished');
		$editor = Application::get()->getRequest()->getUser();
		
		$submissionToShare = $submissionToShareFactory->createPublishedSubmission($journal, $submission, $editor, $datePublished);

		$mailMessageBuilder = new MailMessageBuilder();
		$mailMessageBuilder->buildEmailSender($journal->getLocalizedData('acronym'), $journal->getData('contactEmail'));
		$mailMessageBuilder->buildSubmissionPublishedEmailSubject($submissionToShare);
		$mailMessageBuilder->buildSubmissionPublishedEmailBody($submissionToShare);
		
		$mailMessage = $mailMessageBuilder->getMailMessage();
		$mailMessage->send();
	}

	public function getActions($request, $actionArgs) {
		$actions = parent::getActions($request, $actionArgs);

		if (!$this->getEnabled()) {
			return $actions;
		}

		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		$linkAction = new LinkAction(
			'settings',
			new AjaxModal(
				$router->url(
					$request,
					null,
					null,
					'manage',
					null,
					array(
						'verb' => 'settings',
						'plugin' => $this->getName(),
						'category' => 'generic'
					)
				),
				$this->getDisplayName()
			),
			__('manager.plugins.settings'),
			null
		);

		array_unshift($actions, $linkAction);

		return $actions;
	}

	private function notifyAboutAPIWorking($disableAPI, $notificationMgr,$currentUser) {
		if ($disableAPI){
			$notificationAboutDisabledAPI = __('plugins.generic.boriSharing.disabledAPI');
			$notificationMgr->createTrivialNotification($currentUser->getId(), NOTIFICATION_TYPE_SUCCESS, array('contents' => $notificationAboutDisabledAPI));
		} else {
			$notificationAboutEnabledAPI = __('plugins.generic.boriSharing.enabledAPI');
			$notificationMgr->createTrivialNotification($currentUser->getId(), NOTIFICATION_TYPE_SUCCESS, array('contents' => $notificationAboutEnabledAPI));
		}
	}

	public function manage($args, $request) {
		$journal = $request->getJournal();

		switch($request->getUserVar('verb')) {
			case 'settings':
				$context = $request->getContext();
				AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON,  LOCALE_COMPONENT_PKP_MANAGER);
				$this->import('form.BoriSettingsForm');
				$form = new BoriSettingsForm($this, $context->getId());

				if ($request->getUserVar('save')) {
					$form->readInputData();

					$currentUser = $request->getUser();
					$notificationMgr = new NotificationManager();

					if ($form->validate()) {
						$form->execute();

						$notificationAboutTerms = __('plugins.generic.boriSharing.termsAcceptedSuccessfully');
						$notificationMgr->createTrivialNotification($currentUser->getId(), NOTIFICATION_TYPE_SUCCESS, array('contents' => $notificationAboutTerms));
						
						$disableAPI = $this->getSetting($context->getId(), 'disable_API');
						$this->notifyAboutAPIWorking($disableAPI, $notificationMgr, $currentUser);
						
						$notificationAboutWorking = __('plugins.generic.boriSharing.working');
						$notificationMgr->createTrivialNotification($currentUser->getId(), NOTIFICATION_TYPE_SUCCESS, array('contents' => $notificationAboutWorking));

						$this->notifyThatPluginIsWorking($request, $context);
					} else {
						$form->execute();

						$disableAPI = $this->getSetting($context->getId(), 'disable_API');
						$this->notifyAboutAPIWorking($disableAPI, $notificationMgr, $currentUser);
					}
					return new JSONMessage(true);
				}

				return new JSONMessage(true, $form->fetch($request));
			default:
				return parent::manage($verb, $args, $message, $messageParams);
		}

	}

	private function notifyThatPluginIsWorking($request, $context) {
		$journalInitials = $context->getLocalizedData('acronym');
		$journalName = $context->getLocalizedName();
		$journalURL = $request->getBaseUrl() . '/index.php/' . $context->getData('urlPath');
		$dateStartedWorking = date('d/m/Y', time());

		$mailMessageBuilder = new MailMessageBuilder();
		$mailMessageBuilder->buildEmailSender($journalInitials, $context->getData('contactEmail'));
		$mailMessageBuilder->buildPluginWorkingEmailSubject($journalInitials);
		$mailMessageBuilder->buildPluginWorkingEmailBody($dateStartedWorking, $journalName, $journalURL);
		
		$mailMessage = $mailMessageBuilder->getMailMessage();
		$mailMessage->send();
	}

}
