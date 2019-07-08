<?php declare(strict_types=1);

namespace Tests\Shwrm\Tracking\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TrackingControllerTest extends WebTestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testTrack(?string $trackingCode, string $expectedCode)
    {
        $client = static::createClient([],
            [
                'PHP_AUTH_USER' => 'tracking',
                'PHP_AUTH_PW'   => getenv('TRACKING_PASSWORD'),
            ]
        );

        $client->request('GET', '/track/carrier', ['trackingCode' => $trackingCode]);

        $this->assertEquals($expectedCode, $client->getResponse()->getStatusCode());
    }

    public function dataProvider()
    {
        return [
            [123, Response::HTTP_OK],
            [null, Response::HTTP_BAD_REQUEST],
        ];
    }
}
