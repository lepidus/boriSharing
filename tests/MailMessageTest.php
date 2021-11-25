<?php
use PHPUnit\Framework\TestCase;
import ('plugins.generic.boriSharing.classes.MailMessage');
import ('plugins.generic.boriSharing.classes.Person');
import ('plugins.generic.boriSharing.classes.SubmissionDocument');

class MailMessageTest extends TestCase {

    private $mailMessage;

    private $sender;
    private $recipient;
    private $subject = "Plugin de compartilhamento ativo na revista RBFG";
    private $body = "O plugin de compartilhamento com a Bori foi ativado em 25/12/2021 na Revista Brasileira de Formas Geométricas: <a href=\"http://rbdg.emnuvens.com.br/\">http://rbdg.emnuvens.com.br/</a>";
    private $attachments;

    public function setUp(): void {
        $this->sender = new Person("RBFG", "rbfg@emnuvens.com.br");
        $this->recipient = new Person("", "agenciatestes@lepidus.com.br");
        $this->attachments = [new SubmissionDocument("/public/journals/00/article.pdf", "Final Article")];

        $this->mailMessage = new MailMessage();
        $this->mailMessage->setSender($this->sender);
        $this->mailMessage->setRecipient($this->recipient);
        $this->mailMessage->setSubject($this->subject);
        $this->mailMessage->setBody($this->body);
        $this->mailMessage->setAttachments($this->attachments);
    }

    public function testMessageHasSender(): void {
        $this->assertEquals($this->sender, $this->mailMessage->getSender());
    }

    public function testMessageHasRecipient(): void {
        $this->assertEquals($this->recipient, $this->mailMessage->getRecipient());
    }

    public function testMessageHasSubject(): void {
        $this->assertEquals($this->subject, $this->mailMessage->getSubject());
    }

    public function testMessageHasBody(): void {
        $this->assertEquals($this->body, $this->mailMessage->getBody());   
    }

    public function testMessageHasAttachments(): void {
        $this->assertEquals($this->attachments, $this->mailMessage->getAttachments());
    }
    
}