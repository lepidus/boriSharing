<?php
import('lib.pkp.tests.PKPTestCase');
import('lib.pkp.classes.submission.SubmissionFile');
import ('plugins.generic.boriSharing.classes.BoriAPIClient');


class APIIntegrationTest extends PKPTestCase {

    protected const TESTS_DIRECTORY =  '..'. DIRECTORY_SEPARATOR .'plugins' . DIRECTORY_SEPARATOR . 'generic' . DIRECTORY_SEPARATOR . 'boriSharing' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR;    

    private $locale;
    private $submissionFiles;

    public function setUp(): void {
        $this->locale = 'en_US';

        $firstDocumentPath = self::TESTS_DIRECTORY . 'testOnePage.pdf';
        $firstDocumentName =  'testOnePage.pdf';
        $secondDocumentPath = self::TESTS_DIRECTORY . 'testTwoPages.pdf';
        $secondDocumentName = 'testTwoPages.pdf';

        $this->submissionFiles[]  = $this->createSubmissionFile($firstDocumentPath, $firstDocumentName);
        $this->submissionFiles[]  = $this->createSubmissionFile($secondDocumentPath, $secondDocumentName);
    }

    private function createSubmissionFile($documentPath, $documentName): SubmissionFile {
        $submissionFile = new SubmissionFile();
        $submissionFile->setData('path', $documentPath);
        $submissionFile->setData('name', [$this->locale => $documentName]);

        return $submissionFile;
    }

    public function testUseAuthKeyAndAuthIsSuccessful(): void {

        $userAuthKey = '7815696ecbf1c96e6894b779456d330e';

        $boriAPIClient = new BoriAPIClient($userAuthKey);
        $response = $boriAPIClient->sendSubmissionFiles($this->submissionFiles); 

        $messageExpected = 'The file(s) has been sent';
		$this->assertEquals($messageExpected, $response);
    }

    public function testUseAuthKeyAndAuthFail(): void {

        $userAuthKey = '91281995ca794fafdd10db37a46c5a0786bc0d2a';

        $boriAPIClient = new BoriAPIClient($userAuthKey);
        $response = $boriAPIClient->sendSubmissionFiles($this->submissionFiles); 
    
        $messageExpected = 'The files were not sent due to Authentication Failure';
        $this->assertEquals($messageExpected, $response);
    }

    public function testUseAuthKeyAndConnectionFail(): void {

        $userAuthKey = '7815696ecbf1c96e6894b779456d330e';

        $boriAPIClient = new BoriAPIClient($userAuthKey);
        $response = $boriAPIClient->sendSubmissionFiles($this->submissionFiles); 
    
        $messageExpected = 'The files were not sent due to Connection Failure';
        $this->assertEquals($messageExpected, $response);
    }

}