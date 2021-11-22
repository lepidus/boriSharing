<?php

import('lib.pkp.tests.PKPTestCase');
import('classes.journal.Journal');
import('classes.submission.Submission');
import('classes.publication.Publication');
import('lib.pkp.classes.user.User');
import('classes.article.Author');
import('lib.pkp.classes.submission.SubmissionFile');
import('plugins.generic.boriSharing.classes.SubmissionToShareFactory');

class SubmissionToShareFactoryTest extends PKPTestCase {

    private $submissionToShare;
    private $journal;
    private $submission;
    private $publication;
    private $author;
    private $submissionFile;
    private $editor;
    private $locale = 'pt_BR';

    private $publicationId = 1;
    private $submissionId = 3;
    private $title = "O caso dos cones mágicos";
    private $abstract = "Uma história das formas geométricas cônicas e suas aplicações na terra média";
    private $authorGivenName = "João";
    private $authorFamilyName = "Gandalf";
    private $authorEmail = "joaogandalf@lepidus.com.br";
    private $authorAffiliation = "Lepidus Tecnologia";
    private $journalInitials = "RBFG";
    private $dateAcceptedOriginal = "2021-11-11";
    private $dateAcceptedLocalized = "11/11/2021";
    private $documentPath = "journals/00/articles/0000/submission/proof/final.pdf";
    private $documentName = "Artigo final.pdf";
    private $editorGivenName = "Juliana";
    private $editorFamilyName = "Bolseiro";
    private $editorEmail = "jubolseiro@lepidus.com.br";

    public function setUp(): void {
        parent::setUp();
        
        $this->createJournal();
        $this->createAuthor();
        $this->createSubmissionFile();
        $this->createPublication();        
        $this->createSubmission();
        $this->createEditor();
        
        $submissionToShareFactory = new SubmissionToShareFactory();
        $this->submissionToShare = $submissionToShareFactory->createSubmissionToShare($this->journal, $this->submission, $this->editor, $this->dateAcceptedOriginal, [$this->submissionFile]);
    }

    public function createJournal(): void {
        $this->journal = new Journal();
        $this->journal->setData('acronym', [$this->locale => $this->journalInitials]);
    }

    public function createAuthor(): void {
        $this->author = new Author();
        $this->author->setData('givenName', [$this->locale => $this->authorGivenName]);
        $this->author->setData('familyName', [$this->locale => $this->authorFamilyName]);
        $this->author->setData('email', $this->authorEmail);
        $this->author->setData('affiliation', [$this->locale => $this->authorAffiliation]);
    }

    public function createSubmissionFile(): void {
        $this->submissionFile = new SubmissionFile();
        $this->submissionFile->setData('path', $this->documentPath);
        $this->submissionFile->setData('name', [$this->locale => $this->documentName]);
    }

    public function createPublication(): void {
        $this->publication = new Publication();
        $this->publication->setData('id', $this->publicationId);
        $this->publication->setData('locale', $this->locale);
        $this->publication->setData('title', [$this->locale => $this->title]);
        $this->publication->setData('abstract', [$this->locale => $this->abstract]);
        $this->publication->setData('authors', [$this->author]);
    }

    public function createSubmission(): void {
        $this->submission = new Submission();
        $this->submission->setData('id', $this->submissionId);
        $this->submission->setData('publications', [$this->publication]);
        $this->submission->setData('currentPublicationId', $this->publicationId);
    }

    public function createEditor(): void {
        $this->editor = new User();
        $this->editor->setData('givenName', [$this->locale => $this->editorGivenName]);
        $this->editor->setData('familyName', [$this->locale => $this->editorFamilyName]);
        $this->editor->setData('email', $this->editorEmail);
    }

    public function testSubmissionCreationByFactory(): void {
        $this->assertEquals($this->submissionId, $this->submissionToShare->getId());
        $this->assertEquals($this->title, $this->submissionToShare->getTitle());
        $this->assertEquals($this->abstract, $this->submissionToShare->getAbstract());
        $this->assertEquals($this->journalInitials, $this->submissionToShare->getJournalInitials());
        
        $expectedRecord = "{$this->authorGivenName} {$this->authorFamilyName} ({$this->authorEmail}) - {$this->authorAffiliation}";
        $author = $this->submissionToShare->getAuthors()[0];
        $this->assertEquals($expectedRecord, $author->asRecord());

        $submissionDocument = $this->submissionToShare->getDocuments()[0];
        $expectDocumentPath = rtrim(Config::getVar('files', 'files_dir'), '/') . '/' . $this->documentPath;
        $this->assertEquals($expectDocumentPath, $submissionDocument->getPath());
        $this->assertEquals($this->documentName, $submissionDocument->getName());

        $expectedEditorRecord = "{$this->editorGivenName} {$this->editorFamilyName} ({$this->editorEmail})";
        $editor = $this->submissionToShare->getEditor();
        $this->assertEquals($expectedEditorRecord, $editor->asRecord());
        $this->assertEquals($this->dateAcceptedLocalized, $this->submissionToShare->getDateAccepted());
    }

}
