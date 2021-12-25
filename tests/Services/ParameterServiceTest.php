<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Tests\Services;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Danilovl\ParameterBundle\Services\ParameterService;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use TypeError;

class ParameterServiceTest extends TestCase
{
    private ParameterServiceInterface $parameterService;

    public function setUp(): void
    {
        $parameterBug = new ParameterBag($this->getParameterBagData());
        $this->parameterService = new ParameterService($parameterBug);
    }

    /**
     * @dataProvider dataKeySucceed
     */
    public function testGetSucceed(string $key, mixed $expectedValue): void
    {
        $value = $this->parameterService->get($key);

        $this->assertEquals($expectedValue, $value);
    }

    /**
     * @dataProvider dataKeyFailed
     */
    public function testGetFailed(string $key): void
    {
        $this->expectException(ParameterNotFoundException::class);

        $this->parameterService->get($key);
    }

    /**
     * @dataProvider dataKeyFailed
     */
    public function testGetSucceedIgnore(string $key): void
    {
        $value = $this->parameterService->get($key, true);

        $this->assertEquals(null, $value);
    }

    /**
     * @dataProvider dataKeySucceed
     */
    public function testHasSucceed(string $key): void
    {
        $isExist = $this->parameterService->has($key);

        $this->assertEquals(true, $isExist);
    }

    /**
     * @dataProvider dataKeyFailed
     */
    public function testHasFailed(string $key): void
    {
        $isExist = $this->parameterService->has($key);

        $this->assertEquals(false, $isExist);
    }

    /**
     * @dataProvider dataKeyStringSucceed
     */
    public function testGetStringSucceed(string $key, mixed $expectedValue): void
    {
        $string = $this->parameterService->getString($key);

        $this->assertEquals($expectedValue, $string);
    }

    /**
     * @dataProvider dataKeyStringFailed
     */
    public function testGetStringFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getString($key);
    }

    /**
     * @dataProvider dataKeyIntSucceed
     */
    public function testGetIntSucceed(string $key, mixed $expectedValue): void
    {
        $int = $this->parameterService->getInt($key);

        $this->assertEquals($expectedValue, $int);
    }

    /**
     * @dataProvider dataKeyIntFailed
     */
    public function testGetIntFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getInt($key);
    }

    /**
     * @dataProvider dataKeyFloatSucceed
     */
    public function testGetFloatSucceed(string $key, mixed $expectedValue): void
    {
        $int = $this->parameterService->getFloat($key);

        $this->assertEquals($expectedValue, $int);
    }

    /**
     * @dataProvider dataKeyFloatFailed
     */
    public function testGetFloatFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getFloat($key);
    }

    /**
     * @dataProvider dataKeyBooleanSucceed
     */
    public function testGetBooleanSucceed(string $key, mixed $expectedValue): void
    {
        $boolean = $this->parameterService->getBoolean($key);

        $this->assertEquals($expectedValue, $boolean);
    }

    /**
     * @dataProvider dataKeyBooleanFailed
     */
    public function testGetBooleanFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getBoolean($key);
    }

    /**
     * @dataProvider dataKeyArraySucceed
     */
    public function testGetArraySucceed(string $key, mixed $expectedValue): void
    {
        $array = $this->parameterService->getArray($key);

        $this->assertEquals($expectedValue, $array);
    }

    /**
     * @dataProvider dataKeyArrayFailed
     */
    public function testGetArrayFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getArray($key);
    }

    public function dataKeySucceed(): Generator
    {
        yield ['locale', 'en'];
        yield ['debug', false];
        yield ['project_namespace', 'App'];
        yield ['pagination.default.page', 1];
        yield ['pagination.default.limit', 25];
        yield ['google.api_key', 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'];
        yield ['google.analytics_code', 'UA-X000000'];
    }

    public function dataKeyFailed(): Generator
    {
        yield ['locales'];
        yield ['dug'];
        yield ['projectNamespace'];
        yield ['pagination.default.page.extra'];
        yield ['pagination.defaults'];
        yield ['google.api_keys'];
        yield ['google.analytics_codes'];
    }

    public function dataKeyStringSucceed(): Generator
    {
        yield ['locale', 'en'];
        yield ['project_namespace', 'App'];
        yield ['google.api_key', 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'];
        yield ['google.analytics_code', 'UA-X000000'];
    }

    public function dataKeyStringFailed(): Generator
    {
        yield ['debug'];
        yield ['pagination'];
        yield ['google'];
    }

    public function dataKeyIntSucceed(): Generator
    {
        yield ['pagination.default.page', 1];
        yield ['pagination.default.limit', 25];
    }

    public function dataKeyIntFailed(): Generator
    {
        yield ['locale'];
        yield ['debug'];
        yield ['project_namespace'];
    }

    public function dataKeyFloatSucceed(): Generator
    {
        yield ['price', 200.00];
        yield ['volume', 0.00];
    }

    public function dataKeyFloatFailed(): Generator
    {
        yield ['locale'];
        yield ['debug'];
        yield ['project_namespace'];
    }

    public function dataKeyBooleanSucceed(): Generator
    {
        yield ['debug', $this->getParameterBagData()['debug']];
    }

    public function dataKeyBooleanFailed(): Generator
    {
        yield ['locale'];
        yield ['project_namespace'];
        yield ['pagination'];
        yield ['google'];
    }

    public function dataKeyArraySucceed(): Generator
    {
        yield ['pagination', $this->getParameterBagData()['pagination']];
        yield ['google', $this->getParameterBagData()['google']];
    }

    public function dataKeyArrayFailed(): Generator
    {
        yield ['locale'];
        yield ['debug'];
        yield ['project_namespace'];
        yield ['pagination.default.page'];
        yield ['google.api_key'];
    }

    private function getParameterBagData(): array
    {
        return [
            'locale' => 'en',
            'debug' => false,
            'price' => 200.00,
            'volume' => 0.00,
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
