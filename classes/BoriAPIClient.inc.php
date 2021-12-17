<?php

import('plugins.generic.boriSharing.classes.SubmissionToShareFactory');
import('plugins.generic.boriSharing.classes.MailMessageBuilder');
import('classes.submission.Submission');

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;

class BoriAPIClient {

    public function sendSubmissionFiles($submissionFiles){

        $multipart = $this->createMultipartToRequest($submissionFiles);

		$client = new Client([
			'base_uri' => 'http://localhost:8080/articlefiles',
		]);

		$response = $client->request('POST', '', ['multipart' => $multipart]);

		$responseStatusCode = $response->getStatusCode();
		$successCode = 200;
		
		if( $responseStatusCode != $successCode){
			throw new Exception("Failed to send file to Bori server."); 
		}
    }

    private function createMultipartToRequest($submissionFiles): array{
        $multipart = [];
		$fileIdToSend = 1; 
		foreach($submissionFiles as $submissionFile) {
			$documentPath = rtrim(Config::getVar('files', 'files_dir'), '/') . '/' . $submissionFile->getData('path');
			$documentName = $submissionFile->getLocalizedData('name');
			$multipart[] = [	'name'     => 'file' . $fileIdToSend,
								'filename' => $documentName,
								'contents' => Utils::tryFopen($documentPath, 'r')
							];
			$fileIdToSend += 1;
		}

        return $multipart;
    }

}