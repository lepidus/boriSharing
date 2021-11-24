<?php
/**
 * @file plugins/generic/boriSharing/BoriSharingPlugin.inc.php
 *
 * Copyright (c) 2021 Lepidus Tecnologia
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
import('plugins.generic.boriSharing.classes.SubmissionSharer');

define('AGENCY_EMAIL', "agenciateste@lepidus.com.br");

class BoriSharingPlugin extends GenericPlugin {

	public function register($category, $path, $mainContextId = NULL) {
		$success = parent::register($category, $path, $mainContextId);
		
		if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return true;
		if ($success && $this->getEnabled($mainContextId)) {
			HookRegistry::register('EditorAction::recordDecision', [$this, 'shareWhenArticleApproved']);
			HookRegistry::register('Publication::publish', array($this, 'shareWhenArticlePublished'));
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
			$submissionToShareFactory = new SubmissionToShareFactory();

			$journal = DAORegistry::getDAO('JournalDAO')->getById($submission->getData('contextId'));
			$editor = DAORegistry::getDAO('UserDAO')->getById($editorDecision['editorId']);
			$dateAccepted = $editorDecision['dateDecided'];
			$submissionFiles = $this->getSubmissionFiles($submission);
			$submissionToShare = $submissionToShareFactory->createAcceptedSubmission($journal, $submission, $editor, $dateAccepted, $submissionFiles);
			
			$sender = $journal->getData('contactEmail');
			$submissionSharer = new SubmissionSharer($submissionToShare, $sender, AGENCY_EMAIL);
			$submissionSharer->shareAccepted();
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
		
		$request = Application::get()->getRequest();
		$editor = $request->getUser();
		$submissionToShare = $submissionToShareFactory->createPublishedSubmission($journal, $submission, $editor, $datePublished);

		$sender = $journal->getData('contactEmail');
		$submissionSharer = new SubmissionSharer($submissionToShare, $sender, AGENCY_EMAIL);
		$submissionSharer->sharePublished();
	}

}