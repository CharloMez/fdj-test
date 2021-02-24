<?php
namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FdjApiProxy
{
    public const EURO_MILLIONS_RESULTS_URI = '/api/service-draws/v1/games/euromillions/draws?include=results,addons&range=0-0';

    public function __construct(
        private HttpClientInterface $fdjApiClient,
        private LoggerInterface $logger,
    ) {}

    public function getEuroMillionsResults(): array
    {
        $response = $this->fdjApiClient->request(
            Request::METHOD_GET,
            self::EURO_MILLIONS_RESULTS_URI,
        );

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            $message = 'An error occurred when trying to call FDJ Api.';
            $this->logger->error($message);

            throw new HttpException($response->getStatusCode(), $message);
        }

        return $response->toArray();
    }
}
