<?php

import('lib.pkp.tests.PKPTestCase');
import('classes.journal.Journal');
import('classes.submission.Submission');
import('classes.publication.Publication');
import('lib.pkp.classes.user.User');
import('classes.article.Author');
import('lib.pkp.classes.submission.SubmissionFile');
import('classes.article.ArticleGalley');
import('plugins.generic.boriSharing.classes.SubmissionToShareFactory');

class SubmissionToShareFactoryTest extends PKPTestCase {

    private $submissionToShare;
    private $journal;
    private $submission;
    private $publication;
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
    private $galleyPath = "journals/00/articles/0000/submission/proof/final.pdf";
    private $galleyName = "Artigo final.pdf";
    private $editorGivenName = "Juliana";
    private $editorFamilyName = "Bolseiro";
    private $editorEmail = "jubolseiro@lepidus.com.br";

    public function setUp(): void {
        parent::setUp();
        
        $this->journal = new Journal();
        $this->journal->setData('acronym', [$this->locale => $this->journalInitials]);

        $author = new Author();
        $author->setData('givenName', [$this->locale => $this->authorGivenName]);
        $author->setData('familyName', [$this->locale => $this->authorFamilyName]);
        $author->setData('email', $this->authorEmail);
        $author->setData('affiliation', [$this->locale => $this->authorAffiliation]);

        $submissionFile = new SubmissionFile();
        $submissionFile->setData('path', $this->galleyPath);
        $submissionFile->setData('name', [$this->locale => $this->galleyName]);
        
        $articleGalley = new ArticleGalley();
        $articleGalley->_submissionFile = $submissionFile;

        $this->publication = new Publication();
        $this->publication->setData('id', $this->publicationId);
        $this->publication->setData('locale', $this->locale);
        $this->publication->setData('title', [$this->locale => $this->title]);
        $this->publication->setData('abstract', [$this->locale => $this->abstract]);
        $this->publication->setData('authors', [$author]);
        $this->publication->setData('galleys', [$articleGalley]);
        
        $this->submission = new Submission();
        $this->submission->setData('id', $this->submissionId);
        $this->submission->setData('publications', [$this->publication]);
        $this->submission->setData('currentPublicationId', $this->publicationId);

        $editor = new User();
        $editor->setData('givenName', [$this->locale => $this->editorGivenName]);
        $editor->setData('familyName', [$this->locale => $this->editorFamilyName]);
        $editor->setData('email', $this->editorEmail);
        
        $submissionToShareFactory = new SubmissionToShareFactory();
        $this->submissionToShare = $submissionToShareFactory->createSubmissionToShare($this->journal, $this->submission, $editor, $this->dateAcceptedOriginal);
    }

    public function testSubmissionCreationByFactory(): void {
        $this->assertEquals($this->submissionId, $this->submissionToShare->getId());
        $this->assertEquals($this->title, $this->submissionToShare->getTitle());
        $this->assertEquals($this->abstract, $this->submissionToShare->getAbstract());
        $this->assertEquals($this->journalInitials, $this->submissionToShare->getJournalInitials());
        
        $expectedRecord = "{$this->authorGivenName} {$this->authorFamilyName} ({$this->authorEmail}) - {$this->authorAffiliation}";
        $author = $this->submissionToShare->getAuthors()[0];
        $this->assertEquals($expectedRecord, $author->asRecord());

        $this->assertEquals($this->galleyPath, $this->submissionToShare->getGalley()->getPath());
        $this->assertEquals($this->galleyName, $this->submissionToShare->getGalley()->getName());

        $expectedEditorRecord = "{$this->editorGivenName} {$this->editorFamilyName} ({$this->editorEmail})";
        $editor = $this->submissionToShare->getEditor();
        $this->assertEquals($expectedEditorRecord, $editor->asRecord());
        $this->assertEquals($this->dateAcceptedLocalized, $this->submissionToShare->getDateAccepted());
    }

}
