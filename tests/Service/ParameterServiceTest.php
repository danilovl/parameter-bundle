<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Tests\Service;

use Danilovl\ParameterBundle\Exception\{
    EmptyParameterKeyException,
    InvalidArgumentException,
    ParameterCastException,
    ParameterNotFoundException
};
use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Danilovl\ParameterBundle\Service\ParameterService;
use Danilovl\ParameterBundle\Tests\Mock\EnumMock;
use Generator;
use UnitEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

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

        $this->assertSame($expectedValue, $string);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetStringOrNullSucceed(string $key): void
    {
        $string = $this->parameterService->getStringOrNull($key);

        $this->assertNull($string);
    }

    #[DataProvider('provideGetStringFailedCases')]
    public function testGetStringFailed(string $key, string $actualType): void
    {
        $message = sprintf('Cannot cast parameter "%s" to string (got %s)', $key, $actualType);

        $this->expectException(ParameterCastException::class);
        $this->expectExceptionMessage($message);

        $this->parameterService->getString($key);
    }

    #[DataProvider('provideGetIntSucceedCases')]
    public function testGetIntSucceed(string $key, mixed $expectedValue): void
    {
        $int = $this->parameterService->getInt($key);

        $this->assertSame($expectedValue, $int);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetIntOrNullSucceed(string $key): void
    {
        $int = $this->parameterService->getIntOrNull($key);

        $this->assertNull($int);
    }

    #[DataProvider('provideGetIntFailedCases')]
    public function testGetIntFailed(string $key, string $actualType): void
    {
        $message = sprintf('Cannot cast parameter "%s" to int (got %s)', $key, $actualType);

        $this->expectException(ParameterCastException::class);
        $this->expectExceptionMessage($message);

        $this->parameterService->getInt($key);
    }

    #[DataProvider('provideGetFloatSucceedCases')]
    public function testGetFloatSucceed(string $key, mixed $expectedValue): void
    {
        $float = $this->parameterService->getFloat($key);

        $this->assertSame($expectedValue, $float);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetFloatOrNullSucceed(string $key): void
    {
        $float = $this->parameterService->getFloatOrNull($key);

        $this->assertNull($float);
    }

    #[DataProvider('provideGetFloatFailedCases')]
    public function testGetFloatFailed(string $key, string $actualType): void
    {
        $message = sprintf('Cannot cast parameter "%s" to float (got %s)', $key, $actualType);

        $this->expectException(ParameterCastException::class);
        $this->expectExceptionMessage($message);

        $this->parameterService->getFloat($key);
    }

    #[DataProvider('provideGetBooleanSucceedCases')]
    public function testGetBooleanSucceed(string $key, mixed $expectedValue): void
    {
        $boolean = $this->parameterService->getBoolean($key);

        $this->assertSame($expectedValue, $boolean);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetBooleanOrNullSucceed(string $key): void
    {
        $boolean = $this->parameterService->getBooleanOrNull($key);

        $this->assertNull($boolean);
    }

    #[DataProvider('provideGetBooleanFailedCases')]
    public function testGetBooleanFailed(string $key, string $actualType): void
    {
        $message = sprintf('Cannot cast parameter "%s" to bool (got %s)', $key, $actualType);

        $this->expectException(ParameterCastException::class);
        $this->expectExceptionMessage($message);

        $this->parameterService->getBoolean($key);
    }

    #[DataProvider('provideGetArraySucceedCases')]
    public function testGetArraySucceed(string $key, mixed $expectedValue): void
    {
        $array = $this->parameterService->getArray($key);

        $this->assertSame($expectedValue, $array);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetArrayOrNullSucceed(string $key): void
    {
        $array = $this->parameterService->getArrayOrNull($key);

        $this->assertNull($array);
    }

    #[DataProvider('provideGetArrayFailedCases')]
    public function testGetArrayFailed(string $key, string $actualType): void
    {
        $message = sprintf('Cannot cast parameter "%s" to array (got %s)', $key, $actualType);

        $this->expectException(ParameterCastException::class);
        $this->expectExceptionMessage($message);

        $this->parameterService->getArray($key);
    }

    #[DataProvider('provideGetEnumSucceedCases')]
    public function testGetEnumSucceed(string $key, mixed $expectedValue): void
    {
        $enum = $this->parameterService->getUnitEnum($key);

        $this->assertSame($expectedValue, $enum);
    }

    #[DataProvider('provideDataKeyOrNull')]
    public function testGetEnumOrNullSucceed(string $key): void
    {
        $array = $this->parameterService->getUnitEnumOrNull($key);

        $this->assertNull($array);
    }

    #[DataProvider('provideGetEnumFailedCases')]
    public function testGetEnumFailed(string $key, string $actualType): void
    {
        $message = sprintf('Cannot cast parameter "%s" to UnitEnum (got %s)', $key, $actualType);

        $this->expectException(ParameterCastException::class);
        $this->expectExceptionMessage($message);

        $this->parameterService->getUnitEnum($key);
    }

    #[DataProvider('provideGetOrNullCastFailedCases')]
    public function testGetOrNullCastFailed(
        string $method,
        string $key,
        string $expectedType,
        string $actualType
    ): void {
        $message = sprintf('Cannot cast parameter "%s" to %s (got %s)', $key, $expectedType, $actualType);

        $this->expectException(ParameterCastException::class);
        $this->expectExceptionMessage($message);

        $this->parameterService->{$method}($key);
    }

    #[DataProvider('provideGetOrNullExistingValueCases')]
    public function testGetOrNullExisting(string $method, string $key, mixed $expectedValue): void
    {
        $value = $this->parameterService->{$method}($key);

        $this->assertSame($expectedValue, $value);
    }

    #[DataProvider('provideGetOrNullExistingValueCases')]
    public function testGetOrNullIgnoresDefault(string $method, string $key, mixed $expectedValue): void
    {
        $default = match ($method) {
            'getStringOrNull' => 'default',
            'getIntOrNull' => 999,
            'getFloatOrNull' => 999.9,
            'getBooleanOrNull' => !$expectedValue,
            'getArrayOrNull' => ['default'],
            'getUnitEnumOrNull' => EnumMock::A,
            default => null
        };

        $value = $this->parameterService->{$method}($key, default: $default);

        $this->assertSame($expectedValue, $value);
    }

    public function testGetFloatOrNullFromInt(): void
    {
        $value = $this->parameterService->getFloatOrNull('pagination.default.limit');

        $this->assertSame(25.0, $value);
    }

    public function testGetFloatFromInt(): void
    {
        $value = $this->parameterService->getFloat('zero');

        $this->assertSame(0.0, $value);
    }

    public function testTypedGetterDefaultCastFailed(): void
    {
        $this->expectException(ParameterCastException::class);
        $this->expectExceptionMessage('Cannot cast parameter "locale" to int (got string)');

        $this->parameterService->getInt('locale', default: 42);
    }

    #[DataProvider('provideTypedGetterNotFoundCases')]
    public function testTypedGetterNotFound(string $method): void
    {
        $this->expectException(ParameterNotFoundException::class);
        $this->expectExceptionMessage('Parameter "missing" not found');

        $this->parameterService->{$method}('missing');
    }

    #[DataProvider('provideGetNestedFalsyValueCases')]
    public function testGetNestedFalsyValue(string $key, mixed $expectedValue): void
    {
        $this->assertSame($expectedValue, $this->parameterService->get($key));
        $this->assertTrue($this->parameterService->has($key));
    }

    public function testGetNestedNull(): void
    {
        $this->assertFalse($this->parameterService->has('features.nothing'));
        $this->assertSame('fallback', $this->parameterService->get('features.nothing', ignoreNotFound: true, default: 'fallback'));

        $this->expectException(ParameterNotFoundException::class);
        $this->expectExceptionMessage('Parameter "nothing" not found (nested in "features")');

        $this->parameterService->get('features.nothing');
    }

    #[DataProvider('provideGetNotFoundMessageCases')]
    public function testGetNotFoundMessage(
        string $key,
        ?string $delimiter,
        string $expectedMessage
    ): void {
        $this->expectException(ParameterNotFoundException::class);
        $this->expectExceptionMessage($expectedMessage);

        $this->parameterService->get(key: $key, delimiter: $delimiter);
    }

    #[DataProvider('provideGetNestedInNonArrayCases')]
    public function testGetNestedInNonArray(string $key): void
    {
        $value = $this->parameterService->get(key: $key, ignoreNotFound: true, default: 'fallback');

        $this->assertSame('fallback', $value);
        $this->assertFalse($this->parameterService->has($key));
    }

    public function testGetCustomDelimiter(): void
    {
        $parameterBag = new ParameterBag(self::getParameterBagData());
        $service = new ParameterService($parameterBag, ':');

        $this->assertSame(25, $service->getInt('pagination:default:limit'));
        $this->assertSame(25, $service->getInt('pagination.default.limit', '.'));

        $this->expectException(ParameterNotFoundException::class);
        $this->expectExceptionMessage('Parameter "missing" not found (nested in "pagination:default")');

        $service->get('pagination:default:missing');
    }

    public function testEmptyDelimiterFailed(): void
    {
        $parameterBag = new ParameterBag([]);

        $this->expectException(InvalidArgumentException::class);

        new ParameterService($parameterBag, '');
    }

    public function testGetUnsupportedType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parameter "invalid.object" has unsupported value type "stdClass".');

        $this->parameterService->get('invalid.object');
    }

    public function testGetUnsupportedTypeIgnoreNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->parameterService->get('invalid.object', ignoreNotFound: true, default: 'fallback');
    }

    public function testGetUnsupportedTypeOrNull(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->parameterService->getStringOrNull('invalid.object');
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

    public static function provideGetStringSucceedCases(): Generator
    {
        yield ['empty_string', ''];
        yield ['numeric_string', '42'];
        yield ['locale', 'en'];
        yield ['project_namespace', 'App'];
        yield ['google.api_key', 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'];
        yield ['google.analytics_code', 'UA-X000000'];
    }

    public static function provideDataKeyOrNull(): Generator
    {
        yield ['locale_null'];
        yield ['debug_null'];
        yield ['project_namespace_null'];
        yield ['pagination.default.page_null'];
        yield ['google.api_key_null'];
    }

    public static function provideGetStringFailedCases(): Generator
    {
        yield 'bool' => ['debug', 'bool'];
        yield 'array' => ['pagination', 'array'];
        yield 'nested array' => ['google', 'array'];
        yield 'int' => ['pagination.default.page', 'int'];
        yield 'float' => ['price', 'float'];
        yield 'enum' => ['enum_a', EnumMock::class];
    }

    public static function provideGetIntSucceedCases(): Generator
    {
        yield ['pagination.default.page', 1];
        yield ['pagination.default.limit', 25];
    }

    public static function provideGetIntFailedCases(): Generator
    {
        yield 'string' => ['locale', 'string'];
        yield 'bool' => ['debug', 'bool'];
        yield 'numeric string' => ['numeric_string', 'string'];
        yield 'float' => ['price', 'float'];
        yield 'array' => ['pagination', 'array'];
        yield 'enum' => ['enum_a', EnumMock::class];
    }

    public static function provideGetFloatSucceedCases(): Generator
    {
        yield 'float' => ['price', 200.00];
        yield 'zero float' => ['volume', 0.00];
        yield 'int is converted to float' => ['pagination.default.page', 1.0];
        yield 'nested int is converted to float' => ['pagination.default.limit', 25.0];
        yield 'zero int is converted to float' => ['zero', 0.0];
    }

    public static function provideGetFloatFailedCases(): Generator
    {
        yield 'string' => ['locale', 'string'];
        yield 'bool' => ['debug', 'bool'];
        yield 'numeric string' => ['numeric_string', 'string'];
        yield 'array' => ['pagination', 'array'];
        yield 'enum' => ['enum_a', EnumMock::class];
    }

    public static function provideGetBooleanSucceedCases(): Generator
    {
        yield 'false' => ['debug', false];
        yield 'nested true' => ['features.enabled', true];
    }

    public static function provideGetBooleanFailedCases(): Generator
    {
        yield 'string' => ['locale', 'string'];
        yield 'int' => ['pagination.default.page', 'int'];
        yield 'zero int' => ['zero', 'int'];
        yield 'array' => ['pagination', 'array'];
        yield 'nested array' => ['google', 'array'];
    }

    public static function provideGetArraySucceedCases(): Generator
    {
        yield 'top level' => ['pagination', self::getParameterBagData()['pagination']];
        yield 'nested' => ['pagination.default', ['page' => 1, 'limit' => 25]];
        yield 'google' => ['google', self::getParameterBagData()['google']];
        yield 'empty array' => ['empty_array', []];
    }

    public static function provideGetArrayFailedCases(): Generator
    {
        yield 'string' => ['locale', 'string'];
        yield 'bool' => ['debug', 'bool'];
        yield 'int' => ['pagination.default.page', 'int'];
        yield 'nested string' => ['google.api_key', 'string'];
        yield 'enum' => ['enum_a', EnumMock::class];
    }

    public static function provideGetEnumSucceedCases(): Generator
    {
        yield 'a' => ['enum_a', EnumMock::A];
        yield 'b' => ['enum_b', EnumMock::B];
        yield 'nested' => ['features.mode', EnumMock::B];
    }

    public static function provideGetEnumFailedCases(): Generator
    {
        yield 'string' => ['locale', 'string'];
        yield 'bool' => ['debug', 'bool'];
        yield 'enum backing value string' => ['features.mode_value', 'string'];
        yield 'array' => ['pagination', 'array'];
    }

    public static function provideGetOrNullCastFailedCases(): Generator
    {
        yield 'string from bool' => ['getStringOrNull', 'debug', 'string', 'bool'];
        yield 'string from array' => ['getStringOrNull', 'pagination', 'string', 'array'];
        yield 'int from string' => ['getIntOrNull', 'locale', 'int', 'string'];
        yield 'int from float' => ['getIntOrNull', 'price', 'int', 'float'];
        yield 'float from string' => ['getFloatOrNull', 'locale', 'float', 'string'];
        yield 'float from bool' => ['getFloatOrNull', 'debug', 'float', 'bool'];
        yield 'bool from string' => ['getBooleanOrNull', 'locale', 'bool', 'string'];
        yield 'bool from int' => ['getBooleanOrNull', 'zero', 'bool', 'int'];
        yield 'array from string' => ['getArrayOrNull', 'locale', 'array', 'string'];
        yield 'array from enum' => ['getArrayOrNull', 'enum_a', 'array', EnumMock::class];
        yield 'enum from string' => ['getUnitEnumOrNull', 'locale', 'UnitEnum', 'string'];
        yield 'enum from array' => ['getUnitEnumOrNull', 'pagination', 'UnitEnum', 'array'];
    }

    public static function provideGetOrNullExistingValueCases(): Generator
    {
        yield 'string' => ['getStringOrNull', 'locale', 'en'];
        yield 'empty string' => ['getStringOrNull', 'features.title', ''];
        yield 'int' => ['getIntOrNull', 'pagination.default.page', 1];
        yield 'zero int' => ['getIntOrNull', 'features.count', 0];
        yield 'float' => ['getFloatOrNull', 'price', 200.00];
        yield 'bool true' => ['getBooleanOrNull', 'features.enabled', true];
        yield 'bool false' => ['getBooleanOrNull', 'features.disabled', false];
        yield 'array' => ['getArrayOrNull', 'features.list', ['first', 'second']];
        yield 'enum' => ['getUnitEnumOrNull', 'features.mode', EnumMock::B];
    }

    public static function provideTypedGetterNotFoundCases(): Generator
    {
        yield ['getString'];
        yield ['getInt'];
        yield ['getFloat'];
        yield ['getBoolean'];
        yield ['getArray'];
        yield ['getUnitEnum'];
    }

    public static function provideGetNestedFalsyValueCases(): Generator
    {
        yield 'false' => ['features.disabled', false];
        yield 'zero' => ['features.count', 0];
        yield 'empty string' => ['features.title', ''];
    }

    public static function provideGetNotFoundMessageCases(): Generator
    {
        yield 'first level' => ['locales', null, 'Parameter "locales" not found'];
        yield 'second level' => ['pagination.defaults', null, 'Parameter "defaults" not found (nested in "pagination")'];
        yield 'third level' => ['pagination.default.missing', null, 'Parameter "missing" not found (nested in "pagination.default")'];
        yield 'below scalar' => ['pagination.default.page.extra', null, 'Parameter "extra" not found (nested in "pagination.default.page")'];
        yield 'below top-level scalar' => ['locale.extra', null, 'Parameter "extra" not found (nested in "locale")'];
        yield 'below enum' => ['enum_a.value', null, 'Parameter "value" not found (nested in "enum_a")'];
        yield 'custom delimiter' => ['pagination:default:missing', ':', 'Parameter "missing" not found (nested in "pagination:default")'];
        yield 'multi-char delimiter' => ['pagination->default->page->x', '->', 'Parameter "x" not found (nested in "pagination->default->page")'];
    }

    public static function provideGetNestedInNonArrayCases(): Generator
    {
        yield 'string' => ['locale.0'];
        yield 'bool' => ['debug.value'];
        yield 'int' => ['pagination.default.page.0'];
        yield 'enum' => ['enum_a.value'];
        yield 'enum name' => ['features.mode.name'];
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
            'enum_b' => EnumMock::B,
            'zero' => 0,
            'empty_string' => '',
            'numeric_string' => '42',
            'empty_array' => [],
            'features' => [
                'enabled' => true,
                'disabled' => false,
                'count' => 0,
                'title' => '',
                'nothing' => null,
                'mode' => EnumMock::B,
                'mode_value' => 'b',
                'list' => ['first', 'second']
            ],
            'invalid' => [
                'object' => new stdClass
            ]
        ];
    }
}
