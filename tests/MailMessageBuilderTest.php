<?php
use PHPUnit\Framework\TestCase;
import ('plugins.generic.boriSharing.classes.SubmissionToShare');
import ('plugins.generic.boriSharing.classes.MailMessageBuilder');

class MailMessageBuilderTest extends TestCase {

    private $mailMessageBuilder;
    
    private $submissionToShare;
    private $submissionId = 3532;
    private $submissionTitle = "O caso dos cones mágicos";
    private $submissionAbstract = "Uma história das formas geométricas cônicas e suas aplicações na terra média.";
    private $journalInitials = "RBFG";
    private $dateAcceptedOriginal = "2021-11-11";
    private $dateAcceptedLocalized = "11/11/2021";
    private $datePublishedOriginal = "2021-11-23";
    private $datePublishedLocalized = "23/11/2021";
    private $editor;
    private $authors;

    private $datePluginStartedWorking = "25/12/2021";
    private $journalName = "Revista Brasileira de Formas Geométricas";
    private $journalUrl = "http://rbdg.emnuvens.com.br/";


    public function setUp(): void {
        $this->createSubmissionToShare();
        $this->mailMessageBuilder = new MailMessageBuilder();
    }

    private function createSubmissionToShare() {
        $this->editor = new Person("João Gandalf", "joaogandalf@lepidus.com.br");
        $this->authors = [new Person("Juliana Bolseiro", "jubolseiro@lepidus.com.br", "Lepidus"), new Person("Saruman Medeiros", "saruman@lepidus.com.br", "Lepidus")];
        $this->submissionToShare = new SubmissionToShare();
        
        $this->submissionToShare->setId($this->submissionId);
        $this->submissionToShare->setTitle($this->submissionTitle);
        $this->submissionToShare->setAbstract($this->submissionAbstract);
        $this->submissionToShare->setJournalInitials($this->journalInitials);
        $this->submissionToShare->setDateAccepted($this->dateAcceptedOriginal);
        $this->submissionToShare->setDatePublished($this->datePublishedOriginal);
        $this->submissionToShare->setEditor($this->editor);
        $this->submissionToShare->setAuthors($this->authors);
    }

    public function testBuilderWritesSubmissionAcceptedEmailSubject(): void {
        $expectedAcceptedSubject = "Artigo 3532 aprovado na revista RBFG";
        
        $this->mailMessageBuilder->buildSubmissionAcceptedEmailSubject($this->submissionToShare);
        $mailMessage = $this->mailMessageBuilder->getMailMessage();
        $this->assertEquals($expectedAcceptedSubject, $mailMessage->getSubject());
    }

    public function testBuilderWritesSubmissionPublishedEmailSubject(): void {
        $expectedPublishedSubject= "Artigo 3532 publicado na revista RBFG";
        
        $this->mailMessageBuilder->buildSubmissionPublishedEmailSubject($this->submissionToShare);
        $mailMessage = $this->mailMessageBuilder->getMailMessage();
        $this->assertEquals($expectedPublishedSubject, $mailMessage->getSubject());
    }

    public function testBuilderWritesPluginIsWorkingEmailSubject(): void {
        $expectedPluginWorkingSubject= "Plugin de compartilhamento ativo na revista RBFG";
        
        $this->mailMessageBuilder->buildPluginWorkingEmailSubject($this->journalInitials);
        $mailMessage = $this->mailMessageBuilder->getMailMessage();
        $this->assertEquals($expectedPluginWorkingSubject, $mailMessage->getSubject());
    }

    public function testBuilderWritesSubmissionAcceptedEmailBody(): void {
        $expectedBody = "<strong>Sigla do periódico:</strong> RBFG<br>";
        $expectedBody .= "<strong>Identificador do artigo:</strong> 3532<br>";
        $expectedBody .= "<strong>Título do artigo:</strong> O caso dos cones mágicos<br><br>";
        $expectedBody .= "<strong>Resumo/abstract:</strong>  Uma história das formas geométricas cônicas e suas aplicações na terra média.";
        $expectedBody .= "<strong>Autores:</strong> Juliana Bolseiro (jubolseiro@lepidus.com.br) - Lepidus, Saruman Medeiros (saruman@lepidus.com.br) - Lepidus<br>";
        $expectedBody .= "<strong>Data de aprovação:</strong> 11/11/2021<br>";
        $expectedBody .= "<strong>Editor da revista (ou responsável por aprovar o artigo):</strong> João Gandalf (joaogandalf@lepidus.com.br)<br>";

        $this->mailMessageBuilder->buildSubmissionAcceptedEmailBody($this->submissionToShare);
        $mailMessage = $this->mailMessageBuilder->getMailMessage();
        $this->assertEquals($expectedBody, $mailMessage->getBody());
    }

    public function testBuilderWritesSubmissionPublishedEmailBody(): void {
        $expectedBody = "<strong>Título do artigo:</strong> O caso dos cones mágicos<br>";
        $expectedBody .= "<strong>Data de publicação:</strong> 23/11/2021<br>";
        $expectedBody .= "<strong>Editor(a) que publicou:</strong> João Gandalf (joaogandalf@lepidus.com.br)<br>";

        $this->mailMessageBuilder->buildSubmissionPublishedEmailBody($this->submissionToShare);
        $mailMessage = $this->mailMessageBuilder->getMailMessage();
        $this->assertEquals($expectedBody, $mailMessage->getBody());
    }

    public function testBuilderWritesPluginIsWorkingEmailBody(): void {
        $expectedBody = "O plugin de compartilhamento com a Bori foi ativado em 25/12/2021 na Revista Brasileira de Formas Geométricas: <a href=\"http://rbdg.emnuvens.com.br/\">http://rbdg.emnuvens.com.br/</a>";

        $this->mailMessageBuilder->buildPluginWorkingEmailBody($this->datePluginStartedWorking, $this->journalName, $this->journalUrl);
        $mailMessage = $this->mailMessageBuilder->getMailMessage();
        $this->assertEquals($expectedBody, $mailMessage->getBody());
    }

}