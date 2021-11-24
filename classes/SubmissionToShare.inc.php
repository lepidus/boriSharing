<?php
import ('plugins.generic.boriSharing.classes.Person');
import ('plugins.generic.boriSharing.classes.SubmissionDocument');

class SubmissionToShare {

    private $id;
    private $title;
    private $abstract;
    private $journalInitials;
    private $dateAccepted;
    private $datePublished;
    private $editor;
    private $authors;
    private $documents;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function getAbstract(): string {
        return $this->abstract;
    }

    public function setAbstract(string $abstract) {
        $this->abstract = $abstract;
    }

    public function getJournalInitials(): string {
        return $this->journalInitials;
    }

    public function setJournalInitials(string $journalInitials) {
        $this->journalInitials = $journalInitials;
    }

    public function getDateAccepted(): string {
        return $this->dateAccepted->format("d/m/Y");
    }

    public function setDateAccepted(string $dateAccepted) {
        $this->dateAccepted = new DateTime($dateAccepted);
    }
    
    public function getDatePublished(): string {
        return $this->datePublished->format("d/m/Y");
    }

    public function setDatePublished(string $datePublished) {
        $this->datePublished = new DateTime($datePublished);
    }

    public function getEditor(): Person {
        return $this->editor;
    }

    public function setEditor(Person $editor) {
        $this->editor = $editor;
    }

    public function getAuthors(): array {
        return $this->authors;
    }

    public function setAuthors(array $authors) {
        $this->authors = $authors;
    }

    public function getDocuments(): array {
        return $this->documents;
    }

    public function setDocuments(array $documents) {
        $this->documents = $documents;
    }

    public function getAuthorsAsRecord(): string {
        $records = [];
        
        foreach($this->authors as $author) {
            $records[] = $author->asRecord();
        }

        return implode(", ", $records);
    }
}