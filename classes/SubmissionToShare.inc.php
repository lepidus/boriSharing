<?php
import ('plugins.generic.boriSharing.classes.Person');
import ('plugins.generic.boriSharing.classes.SubmissionGalley');

class SubmissionToShare {

    private $id;
    private $title;
    private $abstract;
    private $journalInitials;
    private $dateAccepted;
    private $researchInstitution;
    private $editor;
    private $authors;
    private $galley;

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

    public function getResearchInstitution(): string {
        return $this->researchInstitution;
    }

    public function setResearchInstitution(string $researchInstitution) {
        $this->researchInstitution = $researchInstitution;
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

    public function getGalley(): SubmissionGalley {
        return $this->galley;
    }

    public function setGalley(SubmissionGalley $galley) {
        $this->galley = $galley;
    }

    public function getAuthorsAsRecord(): string {
        $records = [];
        
        foreach($this->authors as $author) {
            $records[] = $author->asRecord();
        }

        return implode(", ", $records);
    }
}