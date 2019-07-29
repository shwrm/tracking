<?php declare(strict_types=1);

namespace Tests\Shwrm\Tracking\Integrations\Clients\Config;

use PHPUnit\Framework\TestCase;
use Shwrm\Tracking\Exception\MissingCarrierConfigException;
use Shwrm\Tracking\Exception\MissingCarrierConfigIdException;
use Shwrm\Tracking\Integrations\Clients\Config\ConfigResolver;

class ConfigResolverTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testResolve(string $carrier, string $id, array $expectedConfig)
    {
        $resolver = new ConfigResolver($this->getConfig());
        $this->assertEquals($expectedConfig, $resolver->resolve($carrier, $id));
    }

    public function testResolveWithNotExistingCarrier()
    {
        $this->expectException(MissingCarrierConfigException::class);
        $this->expectExceptionMessage('Missing integration config for: non');

        $resolver = new ConfigResolver($this->getConfig());
        $resolver->resolve('non', '1');
    }

    public function testResolveWithNotExistingId()
    {
        $this->expectException(MissingCarrierConfigIdException::class);
        $this->expectExceptionMessage('Missing integration id (1) for carrier: pwr');

        $resolver = new ConfigResolver($this->getConfig());
        $resolver->resolve('pwr', '1');
    }

    public function dataProvider()
    {
        return [
            ['pwr', 'test2', ['partnerId' => '321', 'partnerKey' => 'ewq', 'environment' => 'test']],
            ['dpd', 'test1', ['login' => 'abc', 'password' => '123', 'channel' => '123']],
        ];
    }

    private function getConfig()
    {
        return [
            'pwr' => [
                'test'  => ['partnerId' => '123', 'partnerKey' => 'qwe', 'environment' => 'prod'],
                'test2' => ['partnerId' => '321', 'partnerKey' => 'ewq', 'environment' => 'test'],
            ],
            'dpd' => [
                'test1' => ['login' => 'abc', 'password' => '123', 'channel' => '123'],
            ],
        ];
    }

}
