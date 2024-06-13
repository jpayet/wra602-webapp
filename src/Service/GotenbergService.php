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

    public function generatePdfHtml(ParameterBagInterface $parameterBag, $filePath): string
    {
        $microservice_host = $parameterBag->get('microservice_host');
        $response = $this->client->request(
            'POST',
            $microservice_host.'/generate-pdf-html',
            [
                'body' => [
                    'file' => fopen($filePath, 'r')
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
                'body' => [
                    'url' => $url,
                ],
            ]
        );

        $content = $response->getContent();
        return $content;
    }
}

