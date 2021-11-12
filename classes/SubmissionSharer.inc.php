<?php
import ('plugins.generic.boriSharing.classes.SubmissionToShare');

class SubmissionSharer {

    private $submissionToShare;
    private $sender;
    private $recipient;

    public function __construct(SubmissionToShare $submissionToShare, string $sender, string $recipient) {
        $this->submissionToShare = $submissionToShare;
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    public function getSubmissionToShare(): SubmissionToShare {
        return $this->submissionToShare;
    }

    public function getSender(): string {
        return $this->sender;
    }

    public function getRecipient(): string {
        return $this->recipient;
    }

    public function getSubject(): string {
        return "Artigo {$this->submissionToShare->getId()} aprovado na revista {$this->submissionToShare->getJournalInitials()}";
    }
}