<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Tests\Exception;

use Danilovl\ParameterBundle\Exception\{EmptyParameterKeyException, InvalidArgumentException, ParameterCastException, ParameterNotFoundException};
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testEmptyParameterKeyException(): void
    {
        $exception = new EmptyParameterKeyException;
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertStringContainsString('Parameter key cannot be empty', $exception->getMessage());
    }

    public function testParameterNotFoundExceptionSimple(): void
    {
        $exception = new ParameterNotFoundException('database.host');
        $this->assertStringContainsString('Parameter "database.host" not found', $exception->getMessage());
    }

    public function testParameterNotFoundExceptionNested(): void
    {
        $exception = new ParameterNotFoundException('host', 'database');
        $this->assertStringContainsString('Parameter "host" not found', $exception->getMessage());
        $this->assertStringContainsString('nested in "database"', $exception->getMessage());
    }

    public function testParameterCastException(): void
    {
        $exception = new ParameterCastException('debug', 'string', true);
        $this->assertStringContainsString('Cannot cast parameter "debug" to string', $exception->getMessage());
        $this->assertStringContainsString('got bool', $exception->getMessage());
    }
}
