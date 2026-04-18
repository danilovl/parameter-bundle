<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Exception;

use Exception;

class ParameterNotFoundException extends Exception
{
    public function __construct(string $key, ?string $previousKey = null)
    {
        $message = sprintf('Parameter "%s" not found', $key);
        if ($previousKey !== null) {
            $message .= sprintf(' (nested in "%s")', $previousKey);
        }
        parent::__construct($message);
    }
}
