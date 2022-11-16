<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Service;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParameterService implements ParameterServiceInterface
{
    public const DEFAULT_DELIMITER = '.';

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly string $delimiter = self::DEFAULT_DELIMITER
    ) {}

    private function getParameter(string $key, string $delimiter = null, bool $ignoreNotFound = false): mixed
    {
        $delimiter = $delimiter ?? $this->delimiter;

        $keys = explode($delimiter, $key);
        $configs = $this->parameterBag->get($keys[0]);
        array_shift($keys);

        if (empty($keys)) {
            return $configs;
        }

        foreach ($keys as $key) {
            if (isset($configs[$key])) {
                $configs = $configs[$key];
            } else {
                return $ignoreNotFound ? null : throw new ParameterNotFoundException($key);
            }
        }

        return $configs;
    }

    public function get(string $key, string $delimiter = null, bool $ignoreNotFound = false): mixed
    {
        try {
            return $this->getParameter($key, $delimiter, $ignoreNotFound);
        } catch (ParameterNotFoundException $parameterNotFoundException) {
            return $ignoreNotFound ? null : throw $parameterNotFoundException;
        }
    }

    public function getString(string $key, string $delimiter = null): string
    {
        return $this->get($key, $delimiter);
    }

    public function getInt(string $key, string $delimiter = null): int
    {
        return $this->get($key, $delimiter);
    }

    public function getFloat(string $key, string $delimiter = null): float
    {
        return $this->get($key, $delimiter);
    }

    public function getBoolean(string $key, string $delimiter = null): bool
    {
        return $this->get($key, $delimiter);
    }

    public function getArray(string $key, string $delimiter = null): array
    {
        return $this->get($key, $delimiter);
    }

    public function has(string $key, string $delimiter = null): bool
    {
        try {
            $this->get($key, $delimiter);

            return true;
        } catch (ParameterNotFoundException) {
            return false;
        }
    }
}
