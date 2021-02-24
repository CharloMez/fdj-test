<?php
namespace App\Tests\Unit\Controller;

use App\Controller\EuroMillionsController;
use App\Service\FdjApiProxy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twig\Environment;

class EuroMillionsControllerTest extends TestCase
{
    private MockObject|ContainerInterface|null $container;
    private MockObject|FdjApiProxy|null $fdjApiProxy;
    private MockObject|NormalizerInterface|null $normalizer;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->fdjApiProxy = $this->createMock(FdjApiProxy::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
    }

    protected function tearDown(): void
    {
        $this->container = null;
        $this->fdjApiProxy = null;
        $this->normalizer = null;
    }

    public function testGetEuroMillionsResultsOk()
    {
        $twig = $this->createMock(Environment::class);

        $twig->expects($this->once())
            ->method('render')
            ->with(
                'euro-millions/results.html.twig',
                ['normalize result'],
            )
            ->willReturn('')
        ;

        $this->container->expects($this->once())
            ->method('has')
            ->with('twig')
            ->willReturn(true)
        ;

        $this->container->expects($this->once())
            ->method('get')
            ->with('twig')
            ->willReturn($twig)
        ;

        $this->fdjApiProxy->expects($this->once())
            ->method('getEuroMillionsResults')
            ->willReturn(['result'])
        ;

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with(['result'], EuroMillionsController::DATA_FORMAT)
            ->willReturn(['normalize result'])
        ;

        $controller = new EuroMillionsController(
            $this->fdjApiProxy,
            $this->normalizer,
        );

        $controller->setContainer($this->container);

        $this->assertInstanceOf(
            Response::class,
            $controller->resultsAction()
        );
    }

    public function testGetEuroMillionsResultsKoCallApi()
    {
        $twig = $this->createMock(Environment::class);

        $twig->expects($this->never())
            ->method('render')
        ;

        $this->container->expects($this->never())
            ->method('has')
        ;

        $this->container->expects($this->never())
            ->method('get')
        ;

        $this->normalizer->expects($this->never())
            ->method('normalize')
        ;

        $this->fdjApiProxy->expects($this->once())
            ->method('getEuroMillionsResults')
            ->willThrowException(new HttpException(Response::HTTP_BAD_REQUEST))
        ;

        $controller = new EuroMillionsController(
            $this->fdjApiProxy,
            $this->normalizer,
        );

        $this->expectException(BadRequestHttpException::class);

        $controller->setContainer($this->container);
        $controller->resultsAction();
    }
}
