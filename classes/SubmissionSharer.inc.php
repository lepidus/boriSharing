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
        $emailBody = "<strong>Sigla do periódico:</strong> {$this->submissionToShare->getJournalInitials()}<br>";
        $emailBody .= "<strong>Identificador do artigo:</strong> {$this->submissionToShare->getId()}<br>";
        $emailBody .= "<strong>Título do artigo:</strong> {$this->submissionToShare->getTitle()}<br><br>";
        $emailBody .= "<strong>Resumo/abstract:</strong>  {$this->submissionToShare->getAbstract()}";
        $emailBody .= "<strong>Autores:</strong> {$this->submissionToShare->getAuthorsAsRecord()}<br>";
        $emailBody .= "<strong>Data de aprovação:</strong> {$this->submissionToShare->getDateAccepted()}<br>";
        $emailBody .= "<strong>Editor da revista (ou responsável por aprovar o artigo):</strong> {$this->submissionToShare->getEditor()->asRecord()}<br>";
        
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