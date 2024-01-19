<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Service;

use Danilovl\ParameterBundle\Exception\InvalidArgumentException;
use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use UnitEnum;

readonly class ParameterService implements ParameterServiceInterface
{
    public const string DEFAULT_DELIMITER = '.';

    public function __construct(
        private ParameterBagInterface $parameterBag,
        private string $delimiter = self::DEFAULT_DELIMITER
    ) {
        if (empty($this->delimiter)) {
            throw new InvalidArgumentException('Delimiter cannot be null.');
        }
    }

    private function getParameter(
        string $key,
        string $delimiter = null,
        bool $ignoreNotFound = false
    ): array|bool|string|int|float|UnitEnum|null {
        $delimiter = empty($delimiter) ? $this->delimiter : $delimiter;

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

    public function get(
        string $key,
        string $delimiter = null,
        bool $ignoreNotFound = false
    ): array|bool|string|int|float|UnitEnum|null {
        try {
            return $this->getParameter($key, $delimiter, $ignoreNotFound);
        } catch (ParameterNotFoundException $parameterNotFoundException) {
            return $ignoreNotFound ? null : throw $parameterNotFoundException;
        }
    }

    public function getString(string $key, string $delimiter = null): string
    {
        /** @var string $result */
        $result = $this->get($key, $delimiter);

        return $result;
    }

    public function getInt(string $key, string $delimiter = null): int
    {
        /** @var int $result */
        $result = $this->get($key, $delimiter);

        return $result;
    }

    public function getFloat(string $key, string $delimiter = null): float
    {
        /** @var float $result */
        $result = $this->get($key, $delimiter);

        return $result;
    }

    public function getBoolean(string $key, string $delimiter = null): bool
    {
        /** @var boolean $result */
        $result = $this->get($key, $delimiter);

        return $result;
    }

    public function getArray(string $key, string $delimiter = null): array
    {
        /** @var array $result */
        $result = $this->get($key, $delimiter);

        return $result;
    }

    public function getUnitEnum(string $key, string $delimiter = null): UnitEnum
    {
        /** @var UnitEnum $result */
        $result = $this->get($key, $delimiter);

        return $result;
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
