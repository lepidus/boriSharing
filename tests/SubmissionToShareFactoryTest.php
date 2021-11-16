<?php

import('lib.pkp.tests.PKPTestCase');
import('classes.journal.Journal');
import('classes.submission.Submission');
import('classes.publication.Publication');
import('classes.article.Author');
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
    private $journalInitials = "RBFG";

    public function setUp(): void {
        parent::setUp();
        
        $this->journal = new Journal();
        $this->journal->setData('acronym', [$this->locale => $this->journalInitials]);

        $author = new Author();
        $author->setData('givenName', [$this->locale => $this->authorGivenName]);
        $author->setData('familyName', [$this->locale => $this->authorFamilyName]);
        $author->setData('email', $this->authorEmail);

        $this->publication = new Publication();
        $this->publication->setData('id', $this->publicationId);
        $this->publication->setData('title', [$this->locale => $this->title]);
        $this->publication->setData('abstract', [$this->locale => $this->abstract]);
        $this->publication->setData('authors', [$author]);
        
        $this->submission = new Submission();
        $this->submission->setData('id', $this->submissionId);
        $this->submission->setData('publications', [$this->publication]);
        $this->submission->setData('currentPublicationId', $this->publicationId);
        
        $submissionToShareFactory = new SubmissionToShareFactory();
        $this->submissionToShare = $submissionToShareFactory->createSubmissionToShare($this->journal, $this->submission, $this->locale);
    }

    public function testSubmissionCreationByFactory(): void {
        $this->assertEquals($this->submissionId, $this->submissionToShare->getId());
        $this->assertEquals($this->title, $this->submissionToShare->getTitle());
        $this->assertEquals($this->abstract, $this->submissionToShare->getAbstract());
        $this->assertEquals($this->journalInitials, $this->submissionToShare->getJournalInitials());
        
        $expectedRecord = "{$this->authorGivenName} {$this->authorFamilyName} ({$this->authorEmail})";
        $author = $this->submissionToShare->getAuthors()[0];
        $this->assertEquals($expectedRecord, $author->asRecord());
    }

}
