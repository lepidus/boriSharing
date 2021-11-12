<?php
use PHPUnit\Framework\TestCase;
import ('plugins.generic.boriSharing.classes.SubmissionToShare');
import ('plugins.generic.boriSharing.classes.Person');

error_log("------- TESTES DO BORI SHARING ---------");

class SubmissionToShareTest extends TestCase {

    private $submissionToShare;
    private $submissionId = 7;
    private $submissionTitle = "O caso dos cones mágicos";
    private $submissionAbstract = "Uma história das formas geométricas cônicas e suas aplicações na terra média.";
    private $journalInitials = "RBFG";
    private $dateAcceptedOriginal = "2021-11-11";
    private $dateAcceptedLocalized = "11/11/2021";
    private $researchInstitution = "Lepidus";
    private $editor;
    private $authors;

    public function setUp(): void {
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

    public function testSubmissionHasId(): void {
        $this->assertEquals($this->submissionId, $this->submissionToShare->getId());
    }

    public function testSubmissionHasTitle(): void {
        $this->assertEquals($this->submissionTitle, $this->submissionToShare->getTitle());
    }

    public function testSubmissionHasAbstract(): void {
        $this->assertEquals($this->submissionAbstract, $this->submissionToShare->getAbstract());
    }

    public function testSubmissionHasJournalInitials(): void {
        $this->assertEquals($this->journalInitials, $this->submissionToShare->getJournalInitials());
    }

    public function testSubmissionHasDateAccepted(): void {
        $this->assertEquals($this->dateAcceptedLocalized, $this->submissionToShare->getDateAccepted());
    }

    public function testSubmissionHasResearchInstitution(): void {
        $this->assertEquals($this->researchInstitution, $this->submissionToShare->getResearchInstitution());
    }

    public function testSubmissionHasEditor(): void {
        $this->assertEquals($this->editor, $this->submissionToShare->getEditor());
    }

    public function testSubmissionHasAuthors(): void {
        $this->assertEquals($this->authors, $this->submissionToShare->getAuthors());
    }

    public function testSubmissionAuthorsAsRecord(): void {
        $expectedRecord = $this->authors[0]->asRecord() . ", " . $this->authors[1]->asRecord();

        $this->assertEquals($expectedRecord, $this->submissionToShare->getAuthorsAsRecord());
    }
}