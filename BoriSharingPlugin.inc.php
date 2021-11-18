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
			$submissionToShare = $submissionToShareFactory->createSubmissionToShare($journal, $submission, $editor, $dateAccepted);
			
			$sender = $journal->getData('contactEmail');
			$submissionSharer = new SubmissionSharer($submissionToShare, $sender, AGENCY_EMAIL);
			//$submissionSharer->share();
		}
		
		return false;
	}

}
