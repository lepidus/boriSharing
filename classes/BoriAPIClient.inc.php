<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Exception\ClientException;

class BoriAPIClient {

	private $credentialBase64;

	public function __construct(string $userAuthKey) {
		$stringToEncode = $userAuthKey . ':';
        $this->credentialBase64 = base64_encode($stringToEncode);
    }

    public function sendSubmissionFiles(array $submissionFiles){

        $multipart = $this->createMultipartToRequest($submissionFiles);

		$client = new Client([
			'base_uri' => 'http://localhost:8080/articlefiles',
		]);

		$headers = ['Authorization' => 'Basic ' . $this->credentialBase64];
		
		try {
			$response = $client->request('POST', '', ['headers' => $headers,'multipart' => $multipart]);
		} catch (ClientException $e) {
			$message = 'The files were not sent due to Authentication Failure';
			error_log($message);
			return $message;
		}

		return $response;
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