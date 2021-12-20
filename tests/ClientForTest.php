<?php

use \Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;


require __DIR__ . '/../vendor/autoload.php';

class ClientForTest implements ClientInterface {
    private $exception;

    public function __construct(string $exception){
        $this->exception = $exception;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface{
        $headers = $options['headers'] ?? [];
        $body = $options['body'] ?? null;
        $response = new Response($status = 200, $headers , $body , $version = '1.1');
        
        return $response;
    }

    public function request(string $method, $uri = '', array $options = []): ResponseInterface {
        
        $message = $this->exception;

        $headers = $options['headers'] ?? [];
        $body = $options['body'] ?? null;

        $request = new Request($method, $uri, $headers, $body, $version = '1.1');

        switch ($this->exception) {
            case 'ServerException':
                $response = new Response($status = 500, $headers , $body , $version = '1.1');
                throw new ServerException( $message, $request, $response);
                break;
            case 'ConnectException':
                throw new ConnectException( $message, $request);
                break;
            case 'ClientException':
                $response = new Response($status = 401, $headers , $body , $version = '1.1');
                throw new ClientException( $message, $request,$response);
                break;
        }

        $response = new Response($status = 200, $headers , $body , $version = '1.1');
        return $response;
    }
}