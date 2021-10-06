<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Tests\Services;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Danilovl\ParameterBundle\Services\ParameterService;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ParameterServiceTest extends TestCase
{
    private ParameterServiceInterface $parameterService;

    public function setUp(): void
    {
        $parameterBug = new ParameterBag($this->getParameterBagData());
        $this->parameterService = new ParameterService($parameterBug);
    }

    /**
     * @dataProvider dataSucceed
     */
    public function testGetSucceed(string $key, mixed $expectedValue): void
    {
        $value = $this->parameterService->get($key);

        $this->assertEquals($expectedValue, $value);
    }

    /**
     * @dataProvider dataKeyNotExist
     */
    public function testGetFailed(string $key): void
    {
        $this->expectException(ParameterNotFoundException::class);

        $this->parameterService->get($key);
    }

    /**
     * @dataProvider dataSucceed
     */
    public function testHasSucceed(string $key): void
    {
        $isExist = $this->parameterService->has($key);

        $this->assertEquals(true, $isExist);
    }

    /**
     * @dataProvider dataKeyNotExist
     */
    public function testHasFailed(string $key): void
    {
        $isExist = $this->parameterService->has($key);

        $this->assertEquals(false, $isExist);
    }

    public function dataSucceed(): Generator
    {
        yield ['locale', 'en'];
        yield ['debug', false];
        yield ['project_namespace', 'App'];
        yield ['pagination.default.page', 1];
        yield ['pagination.default.limit', 25];
        yield ['google.api_key', 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'];
        yield ['google.analytics_code', 'UA-X000000'];
    }

    public function dataKeyNotExist(): Generator
    {
        yield ['locales'];
        yield ['dug'];
        yield ['projectNamespace'];
        yield ['pagination.default.page.extra'];
        yield ['pagination.defaults'];
        yield ['google.api_keys'];
        yield ['google.analytics_codes'];
    }

    private function getParameterBagData(): array
    {
        return [
            'locale' => 'en',
            'debug' => false,
            'project_namespace' => 'App',
            'pagination' => [
                'default' => [
                    'page' => 1,
                    'limit' => 25
                ]
            ],
            'google' => [
                'api_key' => 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr',
                'analytics_code' => 'UA-X000000',
            ]
        ];
    }
}
