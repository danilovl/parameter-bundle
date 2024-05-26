[![phpunit](https://github.com/danilovl/parameter-bundle/actions/workflows/phpunit.yml/badge.svg)](https://github.com/danilovl/parameter-bundle/actions/workflows/phpunit.yml)
[![downloads](https://img.shields.io/packagist/dt/danilovl/parameter-bundle)](https://packagist.org/packages/danilovl/parameter-bundle)
[![latest Stable Version](https://img.shields.io/packagist/v/danilovl/parameter-bundle)](https://packagist.org/packages/danilovl/parameter-bundle)
[![license](https://img.shields.io/packagist/l/danilovl/parameter-bundle)](https://packagist.org/packages/danilovl/parameter-bundle)

# ParameterBundle #

## About ##

Symfony bundle provides comfortable getting parameters from config.

### Requirements

* PHP 8.3 or higher
* Symfony 7.0 or higher
* TwigBundle 7.0 or higher

### 1. Installation

Install `danilovl/parameter-bundle` package by Composer:

```bash
composer require danilovl/parameter-bundle
```

Add the `ParameterBundle` to your application's bundles if does not add automatically:

```php
<?php
// config/bundles.php

return [
    // ...
    Danilovl\ParameterBundle\ParameterBundle::class => ['all' => true]
];
```

You can change `delimiter` by you own `delimiter`.

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
        string $delimiter = null,
        bool $ignoreNotFound = false
    ): array|bool|string|int|float|UnitEnum|null;

    public function getString(string $key, string $delimiter = null): string;
    public function getStringOrNull(string $key, string $delimiter = null): ?string;

    public function getInt(string $key, string $delimiter = null): int;
    public function getIntOrNull(string $key, string $delimiter = null): ?int;

    public function getFloat(string $key, string $delimiter = null): float;
    public function getFloatOrNull(string $key, string $delimiter = null): ?float;

    public function getBoolean(string $key, string $delimiter = null): bool;
    public function getBooleanOrNull(string $key, string $delimiter = null): ?bool;

    public function getArray(string $key, string $delimiter = null): array;
    public function getArrayOrNull(string $key, string $delimiter = null): ?array;

    public function getUnitEnum(string $key, string $delimiter = null): UnitEnum;
    public function getUnitEnumOrNull(string $key, string $delimiter = null): ?UnitEnum;

    public function has(string $key, string $delimiter = null): bool;
}

```

### 3. Usage

Project parameters.

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

Get parameters in controller.

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
        $page = $page ?? $this->get(ParameterServiceInterface::class)
                ->getInt('pagination::default::page', '::');

        $limit = $limit ?? $this->get(ParameterServiceInterface::class)
                ->getInt('pagination.default.limit');

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

Get parameters by DI.

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
    
    public function getUserRoles(): array
    {
        return $this->parameterService->getArray('user.roles');
    }
}
```
Ignore `ParameterNotFoundException` if parameter not exist. Method `get` return `null`.

```php
<?php declare(strict_types=1);

namespace App\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;

class WidgetService
{
    public function __construct(private ParameterServiceInterface $parameterService)
    {
    }
    
    public function getWidgetName(): string
    {
        return $this->parameterService->get(key: 'widget.name', ignoreNotFound: true) ?? 'default widget name';
    }
}
```

#### 3.2 Twig extension

Twig functions.

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

Check `debug` parameter in templates.

```twig
{# templates/first.html.twig #}

{% if parameter_has('debug') == true %}
    {#some code#}
{% endif %}

{% if parameter_get_string('locale') == 'en' %}
    {#some code#}
{% endif %}
```

Get `google api` parameters.

```twig
{# templates/first.html.twig #}

{{ parameter_get('google.api_key') }}

{{ parameter_get_string('google.api_key') }}
{{ parameter_get_string('google.analytics_code') }}

{{ parameter_get_int('pagination.default.page') }}
{{ parameter_get_int('pagination.default.limit') }}
```

## License

The ParameterBundle is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
