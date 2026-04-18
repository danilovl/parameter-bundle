<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Tests\Service;

use Danilovl\ParameterBundle\Exception\{
    EmptyParameterKeyException,
    ParameterNotFoundException
};
use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Danilovl\ParameterBundle\Service\ParameterService;
use Danilovl\ParameterBundle\Tests\Mock\EnumMock;
use Generator;
use UnitEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use TypeError;

class ParameterServiceTest extends TestCase
{
    private ParameterServiceInterface $parameterService;

    protected function setUp(): void
    {
        $parameterBag = new ParameterBag($this->getParameterBagData());
        $this->parameterService = new ParameterService($parameterBag);
    }

    #[DataProvider('provideGetSucceedCases')]
    public function testGetSucceed(string $key, mixed $expectedValue): void
    {
        $value = $this->parameterService->get($key);

        $this->assertEquals($expectedValue, $value);
    }

    #[DataProvider('provideGetDelimiterSucceedCases')]
    public function testGetDelimiterSucceed(string $key, ?string $delimiter, mixed $expectedValue): void
    {
        $value = $this->parameterService->get(key: $key, delimiter: $delimiter);

        $this->assertEquals($expectedValue, $value);
    }

    #[DataProvider('provideDataKeyFailed')]
    public function testGetFailed(string $key): void
    {
        $this->expectException(ParameterNotFoundException::class);

        $this->parameterService->get($key);
    }

    #[DataProvider('provideDataKeyFailed')]
    public function testGetSucceedIgnore(string $key): void
    {
        $value = $this->parameterService->get(key: $key, ignoreNotFound: true);

        $this->assertNull($value);
    }

    #[DataProvider('provideHasSucceedCases')]
    public function testHasSucceed(string $key): void
    {
        $isExist = $this->parameterService->has($key);

        $this->assertTrue($isExist);
    }

    #[DataProvider('provideDataKeyFailed')]
    public function testHasFailed(string $key): void
    {
        $isExist = $this->parameterService->has($key);

        $this->assertFalse($isExist);
    }

    #[DataProvider('provideGetStringSucceedCases')]
    public function testGetStringSucceed(string $key, mixed $expectedValue): void
    {
        $string = $this->parameterService->getString($key);

        $this->assertEquals($expectedValue, $string);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetStringOrNullSucceed(string $key): void
    {
        $string = $this->parameterService->getStringOrNull($key);

        $this->assertNull($string);
    }

    #[DataProvider('provideGetStringFailedCases')]
    public function testGetStringFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getString($key);
    }

    #[DataProvider('provideGetIntSucceedCases')]
    public function testGetIntSucceed(string $key, mixed $expectedValue): void
    {
        $int = $this->parameterService->getInt($key);

        $this->assertEquals($expectedValue, $int);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetIntOrNullSucceed(string $key): void
    {
        $int = $this->parameterService->getIntOrNull($key);

        $this->assertNull($int);
    }

    #[DataProvider('provideGetIntFailedCases')]
    public function testGetIntFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getInt($key);
    }

    #[DataProvider('provideGetFloatSucceedCases')]
    public function testGetFloatSucceed(string $key, mixed $expectedValue): void
    {
        $float = $this->parameterService->getFloat($key);

        $this->assertEquals($expectedValue, $float);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetFloatOrNullSucceed(string $key): void
    {
        $float = $this->parameterService->getFloatOrNull($key);

        $this->assertNull($float);
    }

    #[DataProvider('provideGetFloatFailedCases')]
    public function testGetFloatFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getFloat($key);
    }

    #[DataProvider('provideGetBooleanSucceedCases')]
    public function testGetBooleanSucceed(string $key, mixed $expectedValue): void
    {
        $boolean = $this->parameterService->getBoolean($key);

        $this->assertEquals($expectedValue, $boolean);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetBooleanOrNullSucceed(string $key): void
    {
        $boolean = $this->parameterService->getBooleanOrNull($key);

        $this->assertNull($boolean);
    }

    #[DataProvider('provideGetBooleanFailedCases')]
    public function testGetBooleanFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getBoolean($key);
    }

    #[DataProvider('provideGetArraySucceedCases')]
    public function testGetArraySucceed(string $key, mixed $expectedValue): void
    {
        $array = $this->parameterService->getArray($key);

        $this->assertEquals($expectedValue, $array);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetArrayOrNullSucceed(string $key): void
    {
        $array = $this->parameterService->getArrayOrNull($key);

        $this->assertNull($array);
    }

    #[DataProvider('provideGetArrayFailedCases')]
    public function testGetArrayFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getArray($key);
    }

    #[DataProvider('provideGetEnumSucceedCases')]
    public function testGetEnumSucceed(string $key, mixed $expectedValue): void
    {
        $enum = $this->parameterService->getUnitEnum($key);

        $this->assertEquals($expectedValue, $enum);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetEnumOrNullSucceed(string $key): void
    {
        $array = $this->parameterService->getUnitEnumOrNull($key);

        $this->assertNull($array);
    }

    #[DataProvider('provideGetEnumFailedCases')]
    public function testGetEnumFailed(string $key): void
    {
        $this->expectException(TypeError::class);

        $this->parameterService->getUnitEnum($key);
    }

    public static function provideHasSucceedCases(): Generator
    {
        yield ['locale'];
        yield ['debug'];
        yield ['project_namespace'];
        yield ['pagination.default.page'];
        yield ['pagination.default.limit'];
        yield ['google.api_key'];
        yield ['google.analytics_code'];
    }

    public function testGetPerformanceWithManyParameters(): void
    {
        $largeData = [];
        for ($i = 0; $i < 1_000; $i++) {
            $largeData["param_$i"] = "value_$i";
        }

        $parameterBag = new ParameterBag($largeData);
        $service = new ParameterService($parameterBag);

        $start = microtime(true);
        for ($i = 0; $i < 100; $i++) {
            $service->get('param_500');
        }
        $duration = microtime(true) - $start;

        $this->assertLessThan(0.1, $duration);
    }

    #[DataProvider('provideGetWithDeepNestingDefaultValueCases')]
    public function testGetWithDeepNestingDefaultValue(string $key, array|bool|string|int|float|UnitEnum|null $default): void
    {
        $value = $this->parameterService->get(
            key: $key,
            ignoreNotFound: true,
            default: $default
        );
        $this->assertEquals($default, $value);
    }

    public function testGetWithDeepNesting(): void
    {
        $value = $this->parameterService->get('pagination.default.page');
        $this->assertEquals(1, $value);
    }

    public function testGetStringOrNullWithDefaultValue(): void
    {
        $value = $this->parameterService->getStringOrNull('nonexistent.key', default: 'default_string');
        $this->assertEquals('default_string', $value);
    }

    public function testGetArrayWithDefaultValue(): void
    {
        $default = ['key' => 'value'];
        $value = $this->parameterService->getArray('nonexistent.key', default: $default);

        $this->assertEquals($default, $value);
    }

    public function testGetBooleanWithDefaultValue(): void
    {
        $value = $this->parameterService->getBoolean('nonexistent.key', default: true);

        $this->assertTrue($value);
    }

    public function testGetFloatWithDefaultValue(): void
    {
        $value = $this->parameterService->getFloat('nonexistent.key', default: 3.14);

        $this->assertEquals(3.14, $value);
    }

    public function testGetIntWithDefaultValue(): void
    {
        $value = $this->parameterService->getInt('nonexistent.key', default: 42);

        $this->assertEquals(42, $value);
    }

    public function testGetStringWithDefaultValue(): void
    {
        $value = $this->parameterService->getString('nonexistent.key', default: 'fallback_value');

        $this->assertEquals('fallback_value', $value);
    }

    #[DataProvider('provideGetWithDefaultValueCases')]
    public function testGetWithDefaultValue(string $key, array|bool|string|int|float|UnitEnum|null $default, mixed $expectedValue): void
    {
        $value = $this->parameterService->get(
            key: $key,
            ignoreNotFound: true,
            default: $default
        );

        $this->assertEquals($expectedValue, $value);
    }

    public function testGetStringWithEmptyKeyThrowsException(): void
    {
        $this->expectException(EmptyParameterKeyException::class);

        $this->parameterService->getString('');
    }

    public function testGetWithWhitespaceKeyThrowsException(): void
    {
        $this->expectException(EmptyParameterKeyException::class);

        $this->parameterService->get('   ');
    }

    public function testGetWithEmptyKeyThrowsException(): void
    {
        $this->expectException(EmptyParameterKeyException::class);
        $this->parameterService->get('');
    }

    public static function provideGetWithDeepNestingDefaultValueCases(): Generator
    {
        yield 'deeply nested string' => ['very.deep.nested.key', 'default'];
        yield 'deeply nested int' => ['very.deep.nested.key', 0];
        yield 'deeply nested array' => ['very.deep.nested.key', []];
    }

    public static function provideGetWithDefaultValueCases(): Generator
    {
        yield 'string default' => ['nonexistent.key', 'default_value', 'default_value'];
        yield 'int default' => ['nonexistent.key', 42, 42];
        yield 'float default' => ['nonexistent.key', 3.14, 3.14];
        yield 'bool default' => ['nonexistent.key', true, true];
        yield 'array default' => ['nonexistent.key', ['a' => 'b'], ['a' => 'b']];
        yield 'null default' => ['nonexistent.key', null, null];
    }

    public static function provideGetSucceedCases(): Generator
    {
        yield ['locale', 'en'];
        yield ['debug', false];
        yield ['project_namespace', 'App'];
        yield ['pagination.default.page', 1];
        yield ['pagination.default.limit', 25];
        yield ['google.api_key', 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'];
        yield ['google.analytics_code', 'UA-X000000'];
    }

    public static function provideGetDelimiterSucceedCases(): Generator
    {
        yield ['locale', null, 'en'];
        yield ['debug', null, false];
        yield ['project_namespace', null, 'App'];
        yield ['pagination.default.page', '.', 1];
        yield ['pagination:default:limit', ':', 25];
        yield ['google->api_key', '->', 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'];
        yield ['google#analytics_code', '#', 'UA-X000000'];
    }

    public static function provideDataKeyFailed(): Generator
    {
        yield ['locales'];
        yield ['dug'];
        yield ['projectNamespace'];
        yield ['pagination.default.page.extra'];
        yield ['pagination.defaults'];
        yield ['google.api_keys'];
        yield ['google.analytics_codes'];
    }

    public static function provideGetStringSucceedCases(): Generator
    {
        yield ['locale', 'en'];
        yield ['project_namespace', 'App'];
        yield ['google.api_key', 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'];
        yield ['google.analytics_code', 'UA-X000000'];
    }

    public static function provideGetStringFailedCases(): Generator
    {
        yield ['debug'];
        yield ['pagination'];
        yield ['google'];
    }

    public static function provideGetIntSucceedCases(): Generator
    {
        yield ['pagination.default.page', 1];
        yield ['pagination.default.limit', 25];
    }

    public static function provideGetIntFailedCases(): Generator
    {
        yield ['locale'];
        yield ['debug'];
        yield ['project_namespace'];
    }

    public static function provideGetFloatSucceedCases(): Generator
    {
        yield ['price', 200.00];
        yield ['volume', 0.00];
    }

    public static function provideGetFloatFailedCases(): Generator
    {
        yield ['locale'];
        yield ['debug'];
        yield ['project_namespace'];
    }

    public static function provideGetBooleanSucceedCases(): Generator
    {
        yield ['debug', self::getParameterBagData()['debug']];
    }

    public static function provideGetBooleanFailedCases(): Generator
    {
        yield ['locale'];
        yield ['project_namespace'];
        yield ['pagination'];
        yield ['google'];
    }

    public static function provideGetArraySucceedCases(): Generator
    {
        yield ['pagination', self::getParameterBagData()['pagination']];
        yield ['google', self::getParameterBagData()['google']];
    }

    public static function provideGetArrayFailedCases(): Generator
    {
        yield ['locale'];
        yield ['debug'];
        yield ['project_namespace'];
        yield ['pagination.default.page'];
        yield ['google.api_key'];
    }

    public static function provideDataKeyOrNull(): Generator
    {
        yield ['locale_null'];
        yield ['debug_null'];
        yield ['project_namespace_null'];
        yield ['pagination.default.page_null'];
        yield ['google.api_key_null'];
    }

    public static function provideGetEnumSucceedCases(): Generator
    {
        yield ['enum_a', EnumMock::A];
        yield ['enum_b', EnumMock::B];
    }

    public static function provideGetEnumFailedCases(): Generator
    {
        yield ['locale'];
        yield ['debug'];
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
            ],
            'enum_a' => EnumMock::A,
            'enum_b' => EnumMock::B
        ];
    }
}
