<?php
import('plugins.generic.boriSharing.classes.SubmissionToShare');
import('plugins.generic.boriSharing.classes.Person');
import('plugins.generic.boriSharing.classes.SubmissionGalley');
import('classes.submission.Submission');
import('classes.journal.Journal');
import('lib.pkp.classes.user.User');

class SubmissionToShareFactory {

    public function createSubmissionToShare(Journal $journal, Submission $submission, User $editor, string $dateAccepted): SubmissionToShare {
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

        $submissionFile = $publication->getData('galleys')[0]->_submissionFile;
        $galleyPath = $submissionFile->getData('path');
        $galleyName = $submissionFile->getLocalizedData('name');
        $submissionToShare->setGalley(new SubmissionGalley($galleyPath, $galleyName));

        $editorName = $editor->getFullName();
        $editorEmail = $editor->getData('email');
        $submissionToShare->setEditor(new Person($editorName, $editorEmail));
        
        $submissionToShare->setDateAccepted($dateAccepted);

        return $submissionToShare;
    }

}