<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Service;

use Danilovl\ParameterBundle\Exception\{
    EmptyParameterKeyException,
    InvalidArgumentException,
    ParameterNotFoundException
};
use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException as SymfonyParameterNotFoundException;
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
        ?string $delimiter = null,
        bool $ignoreNotFound = false,
        array|bool|string|int|float|UnitEnum|null $default = null
    ): array|bool|string|int|float|UnitEnum|null {
        $this->validateKey($key);

        $delimiter = empty($delimiter) ? $this->delimiter : $delimiter;

        $keys = explode($delimiter, $key);

        try {
            $configs = $this->parameterBag->get($keys[0]);
        } catch (SymfonyParameterNotFoundException) {
            return $ignoreNotFound ? $default : throw new ParameterNotFoundException($keys[0]);
        }

        array_shift($keys);

        if (empty($keys)) {
            return $configs;
        }

        foreach ($keys as $nestedKey) {
            if (isset($configs[$nestedKey])) {
                $configs = $configs[$nestedKey];
            } else {
                return $ignoreNotFound ? $default : throw new ParameterNotFoundException(key: $nestedKey, previousKey: $keys[0]);
            }
        }

        return $configs;
    }

    public function get(
        string $key,
        ?string $delimiter = null,
        bool $ignoreNotFound = false,
        array|bool|string|int|float|UnitEnum|null $default = null
    ): array|bool|string|int|float|UnitEnum|null {
        try {
            return $this->getParameter(
                key: $key,
                delimiter: $delimiter,
                ignoreNotFound: $ignoreNotFound,
                default: $default
            );
        } catch (ParameterNotFoundException|EmptyParameterKeyException $e) {
            return $ignoreNotFound ? $default : throw $e;
        }
    }

    public function getString(string $key, ?string $delimiter = null, ?string $default = null): string
    {
        /** @var string $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: $default !== null,
            default: $default
        );

        return $result;
    }

    public function getStringOrNull(string $key, ?string $delimiter = null, ?string $default = null): ?string
    {
        /** @var string|null $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: true,
            default: $default
        );

        return $result;
    }

    public function getInt(string $key, ?string $delimiter = null, ?int $default = null): int
    {
        /** @var int $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: $default !== null,
            default: $default
        );

        return $result;
    }

    public function getIntOrNull(string $key, ?string $delimiter = null, ?int $default = null): ?int
    {
        /** @var int|null $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: true,
            default: $default
        );

        return $result;
    }

    public function getFloat(string $key, ?string $delimiter = null, ?float $default = null): float
    {
        /** @var float $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: $default !== null,
            default: $default
        );

        return $result;
    }

    public function getFloatOrNull(string $key, ?string $delimiter = null, ?float $default = null): ?float
    {
        /** @var float|null $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: true,
            default: $default
        );

        return $result;
    }

    public function getBoolean(string $key, ?string $delimiter = null, ?bool $default = null): bool
    {
        /** @var boolean $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: $default !== null,
            default: $default
        );

        return $result;
    }

    public function getBooleanOrNull(string $key, ?string $delimiter = null, ?bool $default = null): ?bool
    {
        /** @var boolean|null $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: true,
            default: $default
        );

        return $result;
    }

    public function getArray(string $key, ?string $delimiter = null, ?array $default = null): array
    {
        /** @var array $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: $default !== null,
            default: $default
        );

        return $result;
    }

    public function getArrayOrNull(string $key, ?string $delimiter = null, ?array $default = null): ?array
    {
        /** @var array|null $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: true,
            default: $default
        );

        return $result;
    }

    public function getUnitEnum(string $key, ?string $delimiter = null, ?UnitEnum $default = null): UnitEnum
    {
        /** @var UnitEnum $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: $default !== null,
            default: $default
        );

        return $result;
    }

    public function getUnitEnumOrNull(string $key, ?string $delimiter = null, ?UnitEnum $default = null): ?UnitEnum
    {
        /** @var UnitEnum|null $result */
        $result = $this->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: true,
            default: $default
        );

        return $result;
    }

    public function has(string $key, ?string $delimiter = null): bool
    {
        try {
            $this->validateKey($key);
            $this->get(key: $key, delimiter: $delimiter);

            return true;
        } catch (ParameterNotFoundException|EmptyParameterKeyException) {
            return false;
        }
    }

    private function validateKey(string $key): void
    {
        if (empty(mb_trim($key))) {
            throw new EmptyParameterKeyException;
        }
    }
}
