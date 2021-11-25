<?php

import('lib.pkp.classes.mail.Mail');
import('plugins.generic.boriSharing.classes.Person');

class MailMessage {

    private $sender;
    private $recipient;
    private $subject;
    private $body;
    private $attachments;

    public function send() {
        $mail = new Mail();

        $fromEmail = $this->sender->getEmail();
        $fromName = $this->sender->getName();
        $mail->setFrom($fromEmail, $fromName);
        
        $mail->setRecipients([
            [
                'name' => $this->recipient->getName(),
                'email' => $this->recipient->getEmail(),
            ],
        ]);
        $mail->setSubject($this->subject);
        $mail->setBody($this->body);

        if(!is_null($this->attachments)) {
            foreach($this->attachments as $attachment) {
                $mail->addAttachment($attachment->getPath(), $attachment->getName());
            }
        }

        $mail->send();
    }
 
    public function getSender(): Person {
        return $this->sender;
    }

    public function setSender(Person $sender) {
        $this->sender = $sender;
    }

    public function getRecipient(): Person {
        return $this->recipient;
    }

    public function setRecipient(Person $recipient) {
        $this->recipient = $recipient;
    }

    public function getSubject(): string {
        return $this->subject;
    }

    public function setSubject(string $subject) {
        $this->subject = $subject;
    }

    public function getBody(): string {
        return $this->body;
    }

    public function setBody(string $body) {
        $this->body = $body;
    }

    public function getAttachments(): array {
        return $this->attachments;
    }

    public function setAttachments(array $attachments) {
        $this->attachments = $attachments;
    }
}