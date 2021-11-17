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

    public function getEmailSubject(): string {
        return "Artigo {$this->submissionToShare->getId()} aprovado na revista {$this->submissionToShare->getJournalInitials()}";
    }

    public function getEmailBody(): string {
        $emailBody = "Sigla do periódico: {$this->submissionToShare->getJournalInitials()}\n";
        $emailBody .= "Identificador do artigo: {$this->submissionToShare->getId()}\n";
        $emailBody .= "Título do artigo: {$this->submissionToShare->getTitle()}\n";
        $emailBody .= "Resumo/abstract  {$this->submissionToShare->getAbstract()}\n";
        $emailBody .= "Autores: {$this->submissionToShare->getAuthorsAsRecord()}\n";
        $emailBody .= "Data de aprovação: {$this->submissionToShare->getDateAccepted()}\n";
        $emailBody .= "Editor da revista (ou responsável por aprovar o artigo): {$this->submissionToShare->getEditor()->asRecord()}\n";
        
        return $emailBody;
    }
}