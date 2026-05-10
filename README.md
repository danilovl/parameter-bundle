[![phpunit](https://github.com/danilovl/parameter-bundle/actions/workflows/phpunit.yml/badge.svg)](https://github.com/danilovl/parameter-bundle/actions/workflows/phpunit.yml)
[![downloads](https://img.shields.io/packagist/dt/danilovl/parameter-bundle)](https://packagist.org/packages/danilovl/parameter-bundle)
[![latest Stable Version](https://img.shields.io/packagist/v/danilovl/parameter-bundle)](https://packagist.org/packages/danilovl/parameter-bundle)
[![license](https://img.shields.io/packagist/l/danilovl/parameter-bundle)](https://packagist.org/packages/danilovl/parameter-bundle)

# ParameterBundle #

## About ##

The Symfony bundle provides a convenient way to retrieve parameters from the configuration.

### IDE Plugin

There is a [PHPStorm IDE plugin](https://github.com/danilovl/parameter-bundle-plugin) available for this bundle that provides auto-completion and parameter search assistance in your IDE.

### Requirements

* PHP 8.5 or higher
* Symfony 8.0 or higher
* TwigBundle 8.0 or higher

### 1. Installation

Install the `danilovl/parameter-bundle` package via Composer:

```bash
composer require danilovl/parameter-bundle
```

Add the `ParameterBundle` to your application's bundles if it is not added automatically:

```php
<?php
// config/bundles.php

return [
    // ...
    Danilovl\ParameterBundle\ParameterBundle::class => ['all' => true]
];
```

You can change the delimiter to your own value:

```yaml
danilovl_parameter:
  delimiter: '.'
```

### 2. Available methods

```php
<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Interfaces;

interface ParameterServiceInterface
{
    public function get(
        string $key,
        ?string $delimiter = null,
        bool $ignoreNotFound = false,
        array|bool|string|int|float|UnitEnum|null $default = null
    ): array|bool|string|int|float|UnitEnum|null;

    public function getString(string $key, ?string $delimiter = null, ?string $default = null): string;

    public function getStringOrNull(string $key, ?string $delimiter = null, ?string $default = null): ?string;

    public function getInt(string $key, ?string $delimiter = null, ?int $default = null): int;

    public function getIntOrNull(string $key, ?string $delimiter = null, ?int $default = null): ?int;

    public function getFloat(string $key, ?string $delimiter = null, ?float $default = null): float;

    public function getFloatOrNull(string $key, ?string $delimiter = null, ?float $default = null): ?float;

    public function getBoolean(string $key, ?string $delimiter = null, ?bool $default = null): bool;

    public function getBooleanOrNull(string $key, ?string $delimiter = null, ?bool $default = null): ?bool;

    public function getArray(string $key, ?string $delimiter = null, ?array $default = null): array;

    public function getArrayOrNull(string $key, ?string $delimiter = null, ?array $default = null): ?array;

    public function getUnitEnum(string $key, ?string $delimiter = null, ?UnitEnum $default = null): UnitEnum;

    public function getUnitEnumOrNull(string $key, ?string $delimiter = null, ?UnitEnum $default = null): ?UnitEnum;

    public function has(string $key, ?string $delimiter = null): bool;
}

```

### 3. Usage

Project parameters:

```yaml
# config/services.yaml

parameters:
  locale: 'en'
  debug: false
  price: 200.00
  volume: 0.00
  project_namespace: 'App'
  pagination:
    default:
      page: 1
      limit: 25
  google:
    api_key: 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'
    analytics_code: 'UA-X000000'
```

#### 3.1 Service

Retrieve parameters in the controller using the traditional approach by extending `AbstractController`.

```php
<?php declare(strict_types=1);

namespace App\Controller;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController
{
    protected function createPagination(
        Request $request,
        $query,
        int $page = null,
        int $limit = null,
        array $options = null
    ): PaginationInterface {
        $page = $page ?? $this->get(ParameterServiceInterface::class)->getInt(key: 'pagination::default::page', delimiter: '::');
        $limit = $limit ?? $this->get(ParameterServiceInterface::class)->getInt('pagination.default.limit');

        $pagination = $this->get('knp_paginator');
        if ($options !== null) {
            $pagination->setDefaultPaginatorOptions($options);
        }

        return $pagination->paginate(
            $query,
            $request->query->getInt('page', $page),
            $request->query->getInt('limit', $limit)
        );
    }
}
```

A more preferable way to retrieve parameters is by using dependency injection.

```php
<?php declare(strict_types=1);

namespace App\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;

class UserService
{
    public function __construct(private ParameterServiceInterface $parameterService)
    {
    }
    
    public function getUserId(): int
    {
        return $this->parameterService->getInt('user.id');
    } 

    public function getUserAdminUsers(): ?array
    {
        return $this->parameterService->getArrayOrNull('user.admin_users');
    } 

    public function getUserRoles(): array
    {
        return $this->parameterService->getArray('user.roles');
    }
}
```

Ignore `ParameterNotFoundException` if the parameter does not exist. The `get` method returns `null` or your custom default value.

```php
<?php declare(strict_types=1);

namespace App\Service;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;

class WidgetService
{
    public function __construct(private ParameterServiceInterface $parameterService)
    {
    }
    
    public function getWidgetName(): string
    {
        return $this->parameterService->getString(key: 'widget.name', default: 'default widget name');
    }

    public function getWidgetVersion(): float
    {
        return $this->parameterService->getFloat(key: 'widget.version', default: 1.0);
    }

    public function isWidgetEnabled(): bool
    {
        return $this->parameterService->getBoolean(key: 'widget.enabled', default: true);
    }
}
```

#### 3.2 Exceptions

The bundle provides several custom exceptions for better error handling:

- `ParameterNotFoundException` - Thrown when a parameter or a nested key is not found.
- `EmptyParameterKeyException` - Thrown when an empty string or a string containing only whitespace is passed as a key.
- `InvalidArgumentException` - Thrown for invalid arguments, such as an empty delimiter.

```php
use Danilovl\ParameterBundle\Exception\ParameterNotFoundException;
use Danilovl\ParameterBundle\Exception\EmptyParameterKeyException;

try {
    $value = $this->parameterService->getString('non.existent.key');
} catch (ParameterNotFoundException $e) {
    // Handle missing parameter
} catch (EmptyParameterKeyException $e) {
    // Handle empty key
}
```

#### 3.3 Twig Extension

Twig functions:

```twig
parameter_get
parameter_get_string
parameter_get_string_or_null
parameter_get_int
parameter_get_int_or_null
parameter_get_float
parameter_get_float_or_null
parameter_get_boolean
parameter_get_boolean_or_null
parameter_get_array
parameter_get_array_or_null
parameter_get_unit_enum
parameter_get_unit_enum_or_null
parameter_has
```

Check the `debug` parameter in templates.

```twig
{# templates/first.html.twig #}

{% if parameter_has('debug') == true %}
    {#some code#}
{% endif %}

{% if parameter_get_string('locale') == 'en' %}
    {#some code#}
{% endif %}
```

Get Google API parameters with defaults.

```twig
{# templates/first.html.twig #}

{{ parameter_get('google.api_key', default='fallback_key') }}

{{ parameter_get_string('google.api_key', default='fallback_key') }}
{{ parameter_get_string('google.analytics_code', default='UA-000000') }}

{{ parameter_get_int('pagination.default.page', default=1) }}
{{ parameter_get_int('pagination.default.limit', default=20) }}

{{ parameter_get_boolean('debug', default=true) }}
```

## License

The ParameterBundle is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
