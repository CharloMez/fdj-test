<?php
namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EuroMillionsControllerTest extends WebTestCase
{
    public function testGetResultAction()
    {
        $client = static::createClient();

        $client->request('GET', '/euro-millions/results');
        $this->assertSame(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testResultActionKoMethod()
    {
        $client = static::createClient();

        $client->request('POST', '/euro-millions/results');
        $this->assertSame(
            Response::HTTP_METHOD_NOT_ALLOWED,
            $client->getResponse()->getStatusCode()
        );
    }
}
