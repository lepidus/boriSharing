<?php

import('lib.pkp.classes.mail.Mail');
import('plugins.generic.boriSharing.classes.Person');

class MailSender {

    public function sendMail(Person $sender, Person $recipient, string $subject, string $body, $attachments = array()) {
        $mail = new Mail();

        $fromEmail = $sender->getEmail();
        $fromName = $sender->getName();
        $mail->setFrom($fromEmail, $fromName);
        
        $mail->setRecipients([
            [
                'name' => $recipient->getName(),
                'email' => $recipient->getEmail(),
            ],
        ]);
        $mail->setSubject($subject);
        $mail->setBody($body);

        foreach($attachments as $attachment) {
            $mail->addAttachment($attachment->getPath(), $attachment->getName());
        }

        $mail->send();
    }

}