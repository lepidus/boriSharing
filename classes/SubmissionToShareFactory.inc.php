<?php
import('plugins.generic.boriSharing.classes.SubmissionToShare');
import('plugins.generic.boriSharing.classes.Person');
import('plugins.generic.boriSharing.classes.SubmissionDocument');
import('classes.submission.Submission');
import('classes.publication.Publication');
import('classes.journal.Journal');
import('lib.pkp.classes.user.User');

class SubmissionToShareFactory {

    private $submissionToShare;

    public function __construct() {
        $this->submissionToShare = new SubmissionToShare();
    }

    private function insertMetadata(Journal $journal, Submission $submission, Publication $publication) {
        $this->submissionToShare->setId($submission->getData('id'));
        $this->submissionToShare->setTitle($publication->getLocalizedData('title'));
        $this->submissionToShare->setAbstract($publication->getLocalizedData('abstract'));
        $this->submissionToShare->setJournalInitials($journal->getLocalizedData('acronym'));
    }

    private function insertEditor( User $editor) {
        $editorName = $editor->getFullName();
        $editorEmail = $editor->getData('email');
        $this->submissionToShare->setEditor(new Person($editorName, $editorEmail));

    }

    private function insertAuthors(Publication $publication) {
        $submissionAuthors = [];
        foreach($publication->getData('authors') as $author) {
            $authorName = $author->getFullName();
            $authorEmail = $author->getData('email');
            $authorAffiliation = (!is_null($author->getLocalizedData('affiliation'))) ? ($author->getLocalizedData('affiliation')) : ("");

            $submissionAuthors[] = new Person($authorName, $authorEmail, $authorAffiliation);
        }
        $this->submissionToShare->setAuthors($submissionAuthors);
    }

    private function insertSubmissionFiles(array $submissionFiles){
        $submissionDocuments = [];
        foreach($submissionFiles as $submissionFile) {
            $documentPath = rtrim(Config::getVar('files', 'files_dir'), '/') . '/' . $submissionFile->getData('path');
            $documentName = $submissionFile->getLocalizedData('name');
            $submissionDocuments[] = new SubmissionDocument($documentPath, $documentName);
        }
        $this->submissionToShare->setDocuments($submissionDocuments);
    }

    public function createAcceptedSubmission(Journal $journal, Submission $submission, User $editor, string $dateAccepted, array $submissionFiles): SubmissionToShare {
        $publication = $submission->getCurrentPublication();

        $this->insertMetadata($journal, $submission, $publication);
        $this->insertEditor($editor);
        $this->insertAuthors($publication);
        $this->insertSubmissionFiles($submissionFiles);

        $this->submissionToShare->setDateAccepted($dateAccepted);

        return $this->submissionToShare;
    }

    public function createPublishedSubmission(Journal $journal, Submission $submission, User $editor, string $datePublished): SubmissionToShare {
        $publication = $submission->getCurrentPublication();
        
        $this->insertMetadata($journal, $submission, $publication);
        $this->insertEditor($editor);

        $this->submissionToShare->setDatePublished($datePublished);

        return $this->submissionToShare;
    }

}