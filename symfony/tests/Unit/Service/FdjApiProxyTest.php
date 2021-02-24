<?php
namespace App\Tests\Unit\Service;

use App\Service\FdjApiProxy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FdjApiProxyTest extends TestCase
{
    private MockObject|HttpClientInterface|null $fdjApiClient;
    private MockObject|LoggerInterface|null $logger;

    protected function setUp(): void
    {
        $this->fdjApiClient = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    protected function tearDown(): void
    {
        $this->fdjApiClient = null;
        $this->logger = null;
    }

    public function testGetEuroMillionsResultsOk()
    {
        $response = $this->createMock(ResponseInterface::class);

        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_OK)
        ;

        $response->expects($this->once())
            ->method('toArray')
            ->willReturn($result = ['ok'])
        ;

        $this->fdjApiClient->expects($this->once())
            ->method('request')
            ->with(
                Request::METHOD_GET,
                FdjApiProxy::EURO_MILLIONS_RESULTS_URI,
            )
            ->willReturn($response)
        ;

        $this->logger->expects($this->never())
            ->method('error')
        ;

        $fdjApiProxy = new FdjApiProxy($this->fdjApiClient, $this->logger);
        $this->assertSame($result, $fdjApiProxy->getEuroMillionsResults());
    }

    public function testGetEuroMillionsResultsKoStatusCode()
    {
        $response = $this->createMock(ResponseInterface::class);

        $response->expects($this->any())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_SERVICE_UNAVAILABLE)
        ;

        $response->expects($this->never())
            ->method('toArray')
        ;

        $this->fdjApiClient->expects($this->once())
            ->method('request')
            ->with(
                Request::METHOD_GET,
                FdjApiProxy::EURO_MILLIONS_RESULTS_URI,
            )
            ->willReturn($response)
        ;

        $this->logger->expects($this->once())
            ->method('error')
        ;

        $this->expectException(HttpException::class);
        $fdjApiProxy = new FdjApiProxy($this->fdjApiClient, $this->logger);
        $fdjApiProxy->getEuroMillionsResults();
    }
}
