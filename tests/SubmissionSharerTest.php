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
    private $researchInstitution = "Lepidus";
    private $editor;
    private $authors;

    public function setUp(): void {
        $this->createSubmissionToShare();
        $this->submissionSharer = new SubmissionSharer($this->submissionToShare, $this->sender, $this->recipient);
    }

    private function createSubmissionToShare() {
        $this->editor = new Person("João Gandalf", "joaogandalf@lepidus.com.br");
        $this->authors = [new Person("Juliana Bolseiro", "jubolseiro@lepidus.com.br"), new Person("Saruman Medeiros", "saruman@lepidus.com.br")];
        $this->submissionToShare = new SubmissionToShare();
        
        $this->submissionToShare->setId($this->submissionId);
        $this->submissionToShare->setTitle($this->submissionTitle);
        $this->submissionToShare->setAbstract($this->submissionAbstract);
        $this->submissionToShare->setJournalInitials($this->journalInitials);
        $this->submissionToShare->setDateAccepted($this->dateAcceptedOriginal);
        $this->submissionToShare->setResearchInstitution($this->researchInstitution);
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

    public function testSharerWritesSubject(): void {
        $expectedSubject = "Artigo 3532 aprovado na revista RBFG";

        $this->assertEquals($expectedSubject, $this->submissionSharer->getSubject());
    }

}