<?php
use PHPUnit\Framework\TestCase;
import ('plugins.generic.boriSharing.classes.SubmissionToShare');
import ('plugins.generic.boriSharing.classes.Person');
import ('plugins.generic.boriSharing.classes.SubmissionDocument');

error_log("------- TESTES DO BORI SHARING ---------");

class SubmissionToShareTest extends TestCase {

    private $submissionToShare;
    private $submissionId = 7;
    private $submissionTitle = "O caso dos cones mágicos";
    private $submissionAbstract = "Uma história das formas geométricas cônicas e suas aplicações na terra média.";
    private $journalInitials = "RBFG";
    private $dateAcceptedOriginal = "2021-11-11";
    private $dateAcceptedLocalized = "11/11/2021";
    private $datePublishedOriginal = "2021-11-23";
    private $datePublishedLocalized = "23/11/2021";
    private $editor;
    private $authors;
    private $documents;

    public function setUp(): void {
        $this->editor = new Person("João Gandalf", "joaogandalf@lepidus.com.br", "Lepidus Tecnologia");
        $this->authors = [new Person("Juliana Bolseiro", "jubolseiro@lepidus.com.br", "Lepidus Tecnologia"), new Person("Saruman Medeiros", "saruman@lepidus.com.br", "Lepidus Tecnologia")];
        $this->documents = [new SubmissionDocument("/public/journals/00/article.pdf", "Final Article")];
        $this->submissionToShare = new SubmissionToShare();
        
        $this->submissionToShare->setId($this->submissionId);
        $this->submissionToShare->setTitle($this->submissionTitle);
        $this->submissionToShare->setAbstract($this->submissionAbstract);
        $this->submissionToShare->setJournalInitials($this->journalInitials);
        $this->submissionToShare->setDateAccepted($this->dateAcceptedOriginal);
        $this->submissionToShare->setDatePublished($this->datePublishedOriginal);
        $this->submissionToShare->setEditor($this->editor);
        $this->submissionToShare->setAuthors($this->authors);
        $this->submissionToShare->setDocuments($this->documents);
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

    public function testSubmissionHasEditor(): void {
        $this->assertEquals($this->editor, $this->submissionToShare->getEditor());
    }

    public function testSubmissionHasAuthors(): void {
        $this->assertEquals($this->authors, $this->submissionToShare->getAuthors());
    }

    public function testSubmissionHasDocuments(): void {
        $this->assertEquals($this->documents, $this->submissionToShare->getDocuments());
    }

    public function testSubmissionAuthorsAsRecord(): void {
        $expectedRecord = $this->authors[0]->asRecord() . ", " . $this->authors[1]->asRecord();

        $this->assertEquals($expectedRecord, $this->submissionToShare->getAuthorsAsRecord());
    }

    public function testSubmissionHasDatePublished(): void {
        $this->assertEquals($this->datePublishedLocalized, $this->submissionToShare->getDatePublished());
    }
}