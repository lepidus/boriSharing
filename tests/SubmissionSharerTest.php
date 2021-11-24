<?php
use PHPUnit\Framework\TestCase;
import ('plugins.generic.boriSharing.classes.SubmissionToShare');
import ('plugins.generic.boriSharing.classes.SubmissionSharer');

class SubmissionSharerTest extends TestCase {

    private $submissionSharer;
    private $sender = "admin.journal@lepidus.com.br";
    private $recipient = "agenciateste@lepidus.com.br"; 
    
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

    public function setUp(): void {
        $this->createSubmissionToShare();
        $this->submissionSharer = new SubmissionSharer($this->submissionToShare, $this->sender, $this->recipient);
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

    public function testSharerHasSubmission(): void {
        $this->assertEquals($this->submissionToShare, $this->submissionSharer->getSubmissionToShare());
    }
    
    public function testSharerHasSender(): void {
        $this->assertEquals($this->sender, $this->submissionSharer->getSender());
    }

    public function testSharerHasRecipient(): void {
        $this->assertEquals($this->recipient, $this->submissionSharer->getRecipient());
    }

    public function testSharerWritesAcceptedEmailSubject(): void {
        $expectedAcceptedSubject = "Artigo 3532 aprovado na revista RBFG";
        $this->assertEquals($expectedAcceptedSubject, $this->submissionSharer->getAcceptedEmailSubject());
    }

    public function testSharerWritesPublishedEmailSubject(): void {
        $expectedPublishedSubject= "Artigo 3532 publicado na revista RBFG";
        $this->assertEquals($expectedPublishedSubject, $this->submissionSharer->getPublishedEmailSubject());
    }

    public function testSharerWritesAcceptedEmailBody(): void {
        $expectedBody = "<strong>Sigla do periódico:</strong> RBFG<br>";
        $expectedBody .= "<strong>Identificador do artigo:</strong> 3532<br>";
        $expectedBody .= "<strong>Título do artigo:</strong> O caso dos cones mágicos<br><br>";
        $expectedBody .= "<strong>Resumo/abstract:</strong>  Uma história das formas geométricas cônicas e suas aplicações na terra média.";
        $expectedBody .= "<strong>Autores:</strong> Juliana Bolseiro (jubolseiro@lepidus.com.br) - Lepidus, Saruman Medeiros (saruman@lepidus.com.br) - Lepidus<br>";
        $expectedBody .= "<strong>Data de aprovação:</strong> 11/11/2021<br>";
        $expectedBody .= "<strong>Editor da revista (ou responsável por aprovar o artigo):</strong> João Gandalf (joaogandalf@lepidus.com.br)<br>";

        $this->assertEquals($expectedBody, $this->submissionSharer->getAcceptedEmailBody());
    }

    public function testSharerWritesPublishedEmailBody(): void {
        $expectedBody = "<strong>Título do artigo:</strong> O caso dos cones mágicos<br>";
        $expectedBody .= "<strong>Data de publicação:</strong> 23/11/2021<br>";
        $expectedBody .= "<strong>Editor(a) que publicou:</strong> João Gandalf (joaogandalf@lepidus.com.br)<br>";

        $this->assertEquals($expectedBody, $this->submissionSharer->getPublishedEmailBody());
    }

}