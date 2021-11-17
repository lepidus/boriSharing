<?php
import('plugins.generic.boriSharing.classes.SubmissionToShare');
import('plugins.generic.boriSharing.classes.Person');
import('plugins.generic.boriSharing.classes.SubmissionGalley');
import('classes.submission.Submission');
import('classes.journal.Journal');
import('lib.pkp.classes.user.User');

class SubmissionToShareFactory {

    public function createSubmissionToShare(Journal $journal, Submission $submission, User $editor, string $dateAccepted, string $locale): SubmissionToShare {
        $submissionToShare = new SubmissionToShare();
        $publication = $submission->getCurrentPublication();

        $submissionToShare->setId($submission->getData('id'));
        $submissionToShare->setTitle($publication->getData('title', $locale));
        $submissionToShare->setAbstract($publication->getData('abstract', $locale));
        $submissionToShare->setJournalInitials($journal->getData('acronym', $locale));

        $submissionAuthors = [];
        foreach($publication->getData('authors') as $author) {
            $authorName = $author->getFullName($locale);
            $authorEmail = $author->getData('email');
            $authorAffiliation = $author->getData('affiliation', $locale);

            $submissionAuthors[] = new Person($authorName, $authorEmail, $authorAffiliation);
        }
        $submissionToShare->setAuthors($submissionAuthors);

        $submissionFile = $publication->getData('galleys')[0]->_submissionFile;
        $galleyPath = $submissionFile->getData('path');
        $galleyName = $submissionFile->getData('name', $locale);
        $submissionToShare->setGalley(new SubmissionGalley($galleyPath, $galleyName));

        $editorName = $editor->getFullName($locale);
        $editorEmail = $editor->getData('email');
        $submissionToShare->setEditor(new Person($editorName, $editorEmail));
        
        $submissionToShare->setDateAccepted($dateAccepted);

        return $submissionToShare;
    }

}