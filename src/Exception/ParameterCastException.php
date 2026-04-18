<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Exception;

use TypeError;

class ParameterCastException extends TypeError
{
    public function __construct(string $key, string $expectedType, mixed $actualValue)
    {
        $actualType = get_debug_type($actualValue);
        $message = sprintf(
            'Cannot cast parameter "%s" to %s (got %s)',
            $key,
            $expectedType,
            $actualType
        );
        parent::__construct($message);
    }
}
