<?php
import ('plugins.generic.boriSharing.classes.SubmissionToShare');
import ('plugins.generic.boriSharing.classes.MailMessage');
import ('plugins.generic.boriSharing.classes.Person');

class MailMessageBuilder {

    private $mailMessage;

    public function __construct($agencyEmail) {
        $this->mailMessage = new MailMessage();
        $this->mailMessage->setRecipient(new Person("", $agencyEmail));
    }

    public function buildEmailSender(string $senderName, string $senderEmail) {
        $this->mailMessage->setSender(new Person($senderName, $senderEmail));
    }

    public function buildSubmissionAcceptedEmailSubject( SubmissionToShare $submissionToShare) {
        $this->mailMessage->setSubject("Artigo {$submissionToShare->getId()} aprovado na revista {$submissionToShare->getJournalInitials()}");
    }

    public function buildSubmissionPublishedEmailSubject(SubmissionToShare $submissionToShare) {
        $this->mailMessage->setSubject("Artigo {$submissionToShare->getId()} publicado na revista {$submissionToShare->getJournalInitials()}");
    }

    public function buildPluginWorkingEmailSubject(string $journalInitials) {
        $this->mailMessage->setSubject("Plugin de compartilhamento ativo na revista {$journalInitials}");
    }

    public function buildSubmissionAcceptedEmailBody(SubmissionToShare $submissionToShare) {
        $emailBody = "<strong>Sigla do periódico:</strong> {$submissionToShare->getJournalInitials()}<br>";
        $emailBody .= "<strong>Identificador do artigo:</strong> {$submissionToShare->getId()}<br>";
        $emailBody .= "<strong>Título do artigo:</strong> {$submissionToShare->getTitle()}<br><br>";
        $emailBody .= "<strong>Resumo/abstract:</strong>  {$submissionToShare->getAbstract()}";
        $emailBody .= "<strong>Autores:</strong> {$submissionToShare->getAuthorsAsRecord()}<br>";
        $emailBody .= "<strong>Data de aprovação:</strong> {$submissionToShare->getDateAccepted()}<br>";
        $emailBody .= "<strong>Editor da revista (ou responsável por aprovar o artigo):</strong> {$submissionToShare->getEditor()->asRecord()}<br>";
        
        $this->mailMessage->setBody($emailBody);
    }

    public function buildSubmissionPublishedEmailBody(SubmissionToShare $submissionToShare) {
        $emailBody = "<strong>Título do artigo:</strong> {$submissionToShare->getTitle()}<br>";
        $emailBody .= "<strong>Data de publicação:</strong> {$submissionToShare->getDatePublished()}<br>";
        $emailBody .= "<strong>Editor(a) que publicou:</strong> {$submissionToShare->getEditor()->asRecord()}<br>";

        $this->mailMessage->setBody($emailBody);
    }

    public function buildPluginWorkingEmailBody(string $dateStartedWorking, string $journalName, string $journalURL) {
        $emailBody = "O plugin de compartilhamento com a Bori foi ativado em {$dateStartedWorking} na {$journalName}: <a href=\"{$journalURL}\">{$journalURL}</a>";
        
        $this->mailMessage->setBody($emailBody);
    }

    public function buildEmailAttachments(SubmissionToShare $submissionToShare) {
        $this->mailMessage->setAttachments($submissionToShare->getDocuments());
    }

    public function getMailMessage(): MailMessage {
        return $this->mailMessage;
    }
}

