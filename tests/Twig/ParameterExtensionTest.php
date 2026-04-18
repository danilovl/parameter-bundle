<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Tests\Twig;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Danilovl\ParameterBundle\Twig\ParameterExtension;
use Danilovl\ParameterBundle\Tests\Mock\EnumMock;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;
use Generator;

use function PHPUnit\Framework\once;

class ParameterExtensionTest extends TestCase
{
    private ParameterServiceInterface $parameterService;

    private ParameterExtension $extension;

    protected function setUp(): void
    {
        $this->parameterService = $this->createStub(ParameterServiceInterface::class);
        $this->extension = new ParameterExtension($this->parameterService);
    }

    public function testGetFunctions(): void
    {
        $functions = $this->extension->getFunctions();
        $this->assertCount(14, $functions);

        $expectedNames = [
            'parameter_get',
            'parameter_get_string',
            'parameter_get_string_or_null',
            'parameter_get_int',
            'parameter_get_int_or_null',
            'parameter_get_float',
            'parameter_get_float_or_null',
            'parameter_get_boolean',
            'parameter_get_boolean_or_null',
            'parameter_get_array',
            'parameter_get_array_or_null',
            'parameter_get_unit_enum',
            'parameter_get_unit_enum_or_null',
            'parameter_has'
        ];

        $names = array_map(static fn (TwigFunction $f): string => $f->getName(), $functions);

        sort($names);
        sort($expectedNames);

        $this->assertEquals($expectedNames, $names);
    }

    /**
     * @param non-empty-string $method
     */
    #[DataProvider('provideDelegationCases')]
    public function testDelegation(string $method, array $args, mixed $return): void
    {
        /** @var ParameterServiceInterface&MockObject $parameterService */
        $parameterService = $this->createMock(ParameterServiceInterface::class);
        $parameterService->expects(once())
            ->method($method)
            ->with(...$args)
            ->willReturn($return);

        $extension = new ParameterExtension($parameterService);
        $result = $extension->{$method}(...$args);
        $this->assertEquals($return, $result);
    }

    public static function provideDelegationCases(): Generator
    {
        yield ['get', ['key', '.', true, 'default'], 'value'];
        yield ['getString', ['key', '.', 'default'], 'value'];
        yield ['getStringOrNull', ['key', '.', 'default'], 'value'];
        yield ['getInt', ['key', '.', 42], 42];
        yield ['getIntOrNull', ['key', '.', 42], 42];
        yield ['getFloat', ['key', '.', 3.14], 3.14];
        yield ['getFloatOrNull', ['key', '.', 3.14], 3.14];
        yield ['getBoolean', ['key', '.', true], true];
        yield ['getBooleanOrNull', ['key', '.', true], true];
        yield ['getArray', ['key', '.', ['a']], ['a']];
        yield ['getArrayOrNull', ['key', '.', ['a']], ['a']];
        yield ['getUnitEnum', ['key', '.', EnumMock::A], EnumMock::A];
        yield ['getUnitEnumOrNull', ['key', '.', EnumMock::A], EnumMock::A];
        yield ['has', ['key', '.'], true];
    }
}
