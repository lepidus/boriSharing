<?php
import ('plugins.generic.boriSharing.classes.SubmissionToShare');
import('lib.pkp.classes.mail.Mail');

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

    public function share() {
        $mail = new Mail();

        $fromEmail = $this->sender;
        $fromName = $this->submissionToShare->getJournalInitials();
        $mail->setFrom($fromEmail, $fromName);
        
        $mail->setRecipients([
            [
                'name' => "",
                'email' => $this->recipient,
            ],
        ]);
        $mail->setSubject($this->getEmailSubject());
        $mail->setBody($this->getEmailBody());

        foreach($this->submissionToShare->getDocuments() as $document) {
            $mail->addAttachment($document->getPath(), $document->getName());
        }

        $mail->send();
    }
}