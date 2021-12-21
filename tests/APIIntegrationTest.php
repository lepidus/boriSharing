<?php
import('lib.pkp.tests.PKPTestCase');
import('lib.pkp.classes.submission.SubmissionFile');
import ('plugins.generic.boriSharing.classes.BoriAPIClient');

require('plugins/generic/boriSharing/tests/ClientForTest.php');

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;

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

        $exception = 'NoException';
        $client = new ClientForTest($exception);

        $boriAPIClient = new BoriAPIClient($userAuthKey, $client);
        $response = $boriAPIClient->sendSubmissionFiles($this->submissionFiles); 
        
        $this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals('OK', $response->getReasonPhrase());
    }

    public function testUseAuthKeyAndAuthFail(): void {

        $userAuthKey = '91281995ca794fafdd10db37a46c5a0786bc0d2a';

        $exception = 'ClientException';
        $client = new ClientForTest($exception);

        $boriAPIClient = new BoriAPIClient($userAuthKey, $client);
        try {
            $boriAPIClient->sendSubmissionFiles($this->submissionFiles); 
            $this->fail();
        } catch (ClientException $e) {
            $message = $e->getMessage();
            $messageExpected = 'Unauthorized';
            $this->assertEquals($messageExpected, $message);
        }
    }

    public function testUseAuthKeyAndConnectionFail(): void {

        $userAuthKey = '7815696ecbf1c96e6894b779456d330e';

        $exception = 'ConnectException';
        $client = new ClientForTest($exception);

        $boriAPIClient = new BoriAPIClient($userAuthKey, $client);
        try {
            $boriAPIClient->sendSubmissionFiles($this->submissionFiles); 
            $this->fail();
        } catch (ConnectException $e) {
            $message = $e->getMessage();
            $messageExpected = 'ConnectException';
            $this->assertEquals($messageExpected, $message);
        }
    
    }

    public function testUseAuthKeyAndServerFail(): void {

        $userAuthKey = '7815696ecbf1c96e6894b779456d330e';

        $exception = 'ServerException';
        $client = new ClientForTest($exception);

        $boriAPIClient = new BoriAPIClient($userAuthKey, $client);
        try {
            $boriAPIClient->sendSubmissionFiles($this->submissionFiles); 
            $this->fail();
        } catch (ServerException $e) {
            $message = $e->getMessage();
            $messageExpected = 'Internal Server Error';
            $this->assertEquals($messageExpected, $message);
        }
    }

}