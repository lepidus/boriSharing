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

    public function getAcceptedEmailSubject(): string {
        return "Artigo {$this->submissionToShare->getId()} aprovado na revista {$this->submissionToShare->getJournalInitials()}";
    }

    public function getPublishedEmailSubject(): string {
        return "Artigo {$this->submissionToShare->getId()} publicado na revista {$this->submissionToShare->getJournalInitials()}";
    }

    public function getAcceptedEmailBody(): string {
        $emailBody = "<strong>Sigla do periódico:</strong> {$this->submissionToShare->getJournalInitials()}<br>";
        $emailBody .= "<strong>Identificador do artigo:</strong> {$this->submissionToShare->getId()}<br>";
        $emailBody .= "<strong>Título do artigo:</strong> {$this->submissionToShare->getTitle()}<br><br>";
        $emailBody .= "<strong>Resumo/abstract:</strong>  {$this->submissionToShare->getAbstract()}";
        $emailBody .= "<strong>Autores:</strong> {$this->submissionToShare->getAuthorsAsRecord()}<br>";
        $emailBody .= "<strong>Data de aprovação:</strong> {$this->submissionToShare->getDateAccepted()}<br>";
        $emailBody .= "<strong>Editor da revista (ou responsável por aprovar o artigo):</strong> {$this->submissionToShare->getEditor()->asRecord()}<br>";
        
        return $emailBody;
    }

    public function getPublishedEmailBody(): string {
        $emailBody = "<strong>Título do artigo:</strong> {$this->submissionToShare->getTitle()}<br>";
        $emailBody .= "<strong>Data de publicação:</strong> {$this->submissionToShare->getDatePublished()}<br>";
        $emailBody .= "<strong>Editor(a) que publicou:</strong> {$this->submissionToShare->getEditor()->asRecord()}<br>";

        return $emailBody;
    }

    private function shareByMail($subject, $body, $sendDocuments) {
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
        $mail->setSubject($subject);
        $mail->setBody($body);

        if($sendDocuments) {
            foreach($this->submissionToShare->getDocuments() as $document) {
                $mail->addAttachment($document->getPath(), $document->getName());
            }
        }

        $mail->send();
    }

    public function shareAccepted() {
        $this->shareByMail($this->getAcceptedEmailSubject(), $this->getAcceptedEmailBody(), true);
    }

    public function sharePublished() {
        $this->shareByMail($this->getPublishedEmailSubject(), $this->getPublishedEmailBody(), false);
    }
}