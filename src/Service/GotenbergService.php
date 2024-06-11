<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GotenbergService
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function generatePdfHtml(ParameterBagInterface $parameterBag, $file): string
    {
        $microservice_host = $parameterBag->get('microservice_host');
        $response = $this->client->request(
            'POST',
            $microservice_host.'/generate-pdf-html',
            [
                'headers' => [
                    'Content-Type'=>'multipart/form-data'
                ],
                'body' => [
                    'files' => [
                        'file' => [
                            'name' => 'file',
                            'contents' => $file,
                        ],
                    ],
                ],
            ]
        );
        $content = $response->getContent();
        return $content;
    }

        public function generatePdfUrl(ParameterBagInterface $parameterBag, $url): string
    {
        $microservice_host = $parameterBag->get('microservice_host');
        $response = $this->client->request(
            'POST',
            $microservice_host.'/generate-pdf-url',
            [
                'headers' => [
                    'Content-Type'=>'multipart/form-data'
                ],
                'body' => [
                    'url' => $url,
                ],
            ]
        );



        $content = $response->getContent();
        return $content;
    }
}

