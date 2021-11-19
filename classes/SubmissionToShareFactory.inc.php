<?php
import('plugins.generic.boriSharing.classes.SubmissionToShare');
import('plugins.generic.boriSharing.classes.Person');
import('plugins.generic.boriSharing.classes.SubmissionDocument');
import('classes.submission.Submission');
import('classes.journal.Journal');
import('lib.pkp.classes.user.User');

class SubmissionToShareFactory {

    public function createSubmissionToShare(Journal $journal, Submission $submission, User $editor, string $dateAccepted, array $submissionFiles): SubmissionToShare {
        $submissionToShare = new SubmissionToShare();
        $publication = $submission->getCurrentPublication();

        $submissionToShare->setId($submission->getData('id'));
        $submissionToShare->setTitle($publication->getLocalizedData('title'));
        $submissionToShare->setAbstract($publication->getLocalizedData('abstract'));
        $submissionToShare->setJournalInitials($journal->getLocalizedData('acronym'));

        $submissionAuthors = [];
        foreach($publication->getData('authors') as $author) {
            $authorName = $author->getFullName();
            $authorEmail = $author->getData('email');
            $authorAffiliation = (!is_null($author->getLocalizedData('affiliation'))) ? ($author->getLocalizedData('affiliation')) : ("");

            $submissionAuthors[] = new Person($authorName, $authorEmail, $authorAffiliation);
        }
        $submissionToShare->setAuthors($submissionAuthors);

        $submissionDocuments = [];
        foreach($submissionFiles as $submissionFile) {
            $documentPath = rtrim(Config::getVar('files', 'files_dir'), '/') . '/' . $submissionFile->getData('path');
            $documentName = $submissionFile->getLocalizedData('name');
            $submissionDocuments[] = new SubmissionDocument($documentPath, $documentName);
        }
        $submissionToShare->setDocuments($submissionDocuments);

        $editorName = $editor->getFullName();
        $editorEmail = $editor->getData('email');
        $submissionToShare->setEditor(new Person($editorName, $editorEmail));
        
        $submissionToShare->setDateAccepted($dateAccepted);

        return $submissionToShare;
    }

}