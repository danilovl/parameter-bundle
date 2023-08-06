<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Tests\Service;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Danilovl\ParameterBundle\Service\ParameterService;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('dataKeySucceed')]
    public function testGetSucceed(string $key, mixed $expectedValue): void
    {
        $value = $this->parameterService->get($key);

        $this->assertEquals($expectedValue, $value);
    }

    #[DataProvider('dataKeyDelimiterSucceed')]
    public function testGetDelimiterSucceed(string $key, ?string $delimiter, mixed $expectedValue): void
    {
        $value = $this->parameterService->get(key: $key, delimiter: $delimiter);

        $this->assertEquals($expectedValue, $value);
    }

    #[DataProvider('dataKeyFailed')]
    public function testGetFailed(string $key): void
    {
        $this->expectException(ParameterNotFoundException::class);

        $this->parameterService->get($key);
    }

    #[DataProvider('dataKeyFailed')]
    public function testGetSucceedIgnore(string $key): void
    {
        $value = $this->parameterService->get(key: $key, ignoreNotFound: true);

        $this->assertNull($value);
    }

    #[DataProvider('dataKeySucceed')]
    public function testHasSucceed(string $key): void
    {
        $isExist = $this->parameterService->has($key);

        $this->assertTrue($isExist);
    }

    #[DataProvider('dataKeyFailed')]
    public function testHasFailed(string $key): void
    {
        $isExist = $this->parameterService->has($key);

        $this->assertFalse($isExist);
    }

    #[DataProvider('dataKeyStringSucceed')]
    public function testGetStringSucceed(string $key, mixed $expectedValue): void
    {
        $string = $this->parameterService->getString($key);

        $this->assertEquals($expectedValue, $string);
    }

    #[DataProvider('dataKeyStringFailed')]
    public function testGetStringFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getString($key);
    }

    #[DataProvider('dataKeyIntSucceed')]
    public function testGetIntSucceed(string $key, mixed $expectedValue): void
    {
        $int = $this->parameterService->getInt($key);

        $this->assertEquals($expectedValue, $int);
    }

    #[DataProvider('dataKeyIntFailed')]
    public function testGetIntFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getInt($key);
    }

    #[DataProvider('dataKeyFloatSucceed')]
    public function testGetFloatSucceed(string $key, mixed $expectedValue): void
    {
        $int = $this->parameterService->getFloat($key);

        $this->assertEquals($expectedValue, $int);
    }

    #[DataProvider('dataKeyFloatFailed')]
    public function testGetFloatFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getFloat($key);
    }

    #[DataProvider('dataKeyBooleanSucceed')]
    public function testGetBooleanSucceed(string $key, mixed $expectedValue): void
    {
        $boolean = $this->parameterService->getBoolean($key);

        $this->assertEquals($expectedValue, $boolean);
    }

    #[DataProvider('dataKeyBooleanFailed')]
    public function testGetBooleanFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getBoolean($key);
    }

    #[DataProvider('dataKeyArraySucceed')]
    public function testGetArraySucceed(string $key, mixed $expectedValue): void
    {
        $array = $this->parameterService->getArray($key);

        $this->assertEquals($expectedValue, $array);
    }

    #[DataProvider('dataKeyArrayFailed')]
    public function testGetArrayFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getArray($key);
    }

    public static function dataKeySucceed(): Generator
    {
        yield ['locale', 'en'];
        yield ['debug', false];
        yield ['project_namespace', 'App'];
        yield ['pagination.default.page', 1];
        yield ['pagination.default.limit', 25];
        yield ['google.api_key', 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'];
        yield ['google.analytics_code', 'UA-X000000'];
    }

    public static function dataKeyDelimiterSucceed(): Generator
    {
        yield ['locale', null, 'en'];
        yield ['debug', null, false];
        yield ['project_namespace', null, 'App'];
        yield ['pagination.default.page', '.', 1];
        yield ['pagination:default:limit', ':', 25];
        yield ['google->api_key', '->', 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'];
        yield ['google#analytics_code', '#', 'UA-X000000'];
    }

    public static function dataKeyFailed(): Generator
    {
        yield ['locales'];
        yield ['dug'];
        yield ['projectNamespace'];
        yield ['pagination.default.page.extra'];
        yield ['pagination.defaults'];
        yield ['google.api_keys'];
        yield ['google.analytics_codes'];
    }

    public static function dataKeyStringSucceed(): Generator
    {
        yield ['locale', 'en'];
        yield ['project_namespace', 'App'];
        yield ['google.api_key', 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'];
        yield ['google.analytics_code', 'UA-X000000'];
    }

    public static function dataKeyStringFailed(): Generator
    {
        yield ['debug'];
        yield ['pagination'];
        yield ['google'];
    }

    public static function dataKeyIntSucceed(): Generator
    {
        yield ['pagination.default.page', 1];
        yield ['pagination.default.limit', 25];
    }

    public static function dataKeyIntFailed(): Generator
    {
        yield ['locale'];
        yield ['debug'];
        yield ['project_namespace'];
    }

    public static function dataKeyFloatSucceed(): Generator
    {
        yield ['price', 200.00];
        yield ['volume', 0.00];
    }

    public static function dataKeyFloatFailed(): Generator
    {
        yield ['locale'];
        yield ['debug'];
        yield ['project_namespace'];
    }

    public static function dataKeyBooleanSucceed(): Generator
    {
        yield ['debug', self::getParameterBagData()['debug']];
    }

    public static function dataKeyBooleanFailed(): Generator
    {
        yield ['locale'];
        yield ['project_namespace'];
        yield ['pagination'];
        yield ['google'];
    }

    public static function dataKeyArraySucceed(): Generator
    {
        yield ['pagination', self::getParameterBagData()['pagination']];
        yield ['google', self::getParameterBagData()['google']];
    }

    public static function dataKeyArrayFailed(): Generator
    {
        yield ['locale'];
        yield ['debug'];
        yield ['project_namespace'];
        yield ['pagination.default.page'];
        yield ['google.api_key'];
    }

    private static function getParameterBagData(): array
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
                'analytics_code' => 'UA-X000000'
            ]
        ];
    }
}
