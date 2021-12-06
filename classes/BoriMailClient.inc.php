<?php

import('plugins.generic.boriSharing.classes.SubmissionToShareFactory');
import('plugins.generic.boriSharing.classes.MailMessageBuilder');
import('classes.submission.Submission');

class BoriMailClient {

    private $submission;
    private $editorDecision;
    private $submissionFiles;

    public function __construct(Submission $submission, array $editorDecision, array $submissionFiles) {
        $this->submission = $submission;
        $this->editorDecision = $editorDecision;
        $this->submissionFiles = $submissionFiles;
    }

    private function createMailMessage(): MailMessage {
        $submissionToShareFactory = new SubmissionToShareFactory();

		$journal = DAORegistry::getDAO('JournalDAO')->getById($this->submission->getData('contextId'));
		$editor = DAORegistry::getDAO('UserDAO')->getById($this->editorDecision['editorId']);
		$dateAccepted = $this->editorDecision['dateDecided'];
        
		$submissionToShare = $submissionToShareFactory->createAcceptedSubmission($journal, $this->submission, $editor, $dateAccepted, $this->submissionFiles);
		
		$mailMessageBuilder = new MailMessageBuilder();
		$mailMessageBuilder->buildEmailSender($journal->getLocalizedData('acronym'), $journal->getData('contactEmail'));
		$mailMessageBuilder->buildSubmissionAcceptedEmailSubject($submissionToShare);
		$mailMessageBuilder->buildSubmissionAcceptedEmailBody($submissionToShare);
		$mailMessageBuilder->buildEmailAttachments($submissionToShare);

        $mailMessage = $mailMessageBuilder->getMailMessage();

        return $mailMessage;
    }

    public function sendMail(){
		$mailMessage = $this->createMailMessage();
		$mailMessage->send();
    }
    
}