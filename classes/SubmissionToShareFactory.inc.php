<?php
import('plugins.generic.boriSharing.classes.SubmissionToShare');
import('plugins.generic.boriSharing.classes.Person');
import('classes.submission.Submission');
import('classes.journal.Journal');

class SubmissionToShareFactory {

    public function createSubmissionToShare(Journal $journal, Submission $submission, string $locale): SubmissionToShare {
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

            $submissionAuthors[] = new Person($authorName, $authorEmail);
        }
        $submissionToShare->setAuthors($submissionAuthors);

        return $submissionToShare;
    }

}