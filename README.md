[![phpunit](https://github.com/danilovl/parameter-bundle/actions/workflows/phpunit.yml/badge.svg)](https://github.com/danilovl/parameter-bundle/actions/workflows/phpunit.yml)
[![downloads](https://img.shields.io/packagist/dt/danilovl/parameter-bundle)](https://packagist.org/packages/danilovl/parameter-bundle)
[![latest Stable Version](https://img.shields.io/packagist/v/danilovl/parameter-bundle)](https://packagist.org/packages/danilovl/parameter-bundle)
[![license](https://img.shields.io/packagist/l/danilovl/parameter-bundle)](https://packagist.org/packages/danilovl/parameter-bundle)

# ParameterBundle #

## About ##

Symfony bundle provides comfortable getting parameters from config.

### Requirements 

  * PHP 8.0.0 or higher
  * Symfony 5.0 or higher
  * TwigBundle 5.0 or higher

### 1. Installation

Install `danilovl/parameter-bundle` package by Composer:
 
``` bash
$ composer require danilovl/parameter-bundle
```
Add the `ParameterBundle` to your application's bundles if does not add automatically:

``` php
<?php
// config/bundles.php

return [
    // ...
    Danilovl\ParameterBundle\ParameterBundle::class => ['all' => true]
];
```

### 2. Usage

Project parameters.

```yaml
# config/services.yaml

parameters:
  locale: 'en'
  debug: false
  project_namespace: 'App'
  pagination:
    default:
      page: 1
      limit: 25
  google:
    api_key: 'AzT6Ga0A46K3pUAdQKLwr-zT6Ga0A46K3pUAdQKLwr'
    analytics_code: 'UA-X000000'
```

#### 2.1 Service

Get parameters in controller.

```php
<?php declare(strict_types=1);

namespace App\Controller;

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
        $page = $page ?? $this->get('danilovl.parameter')
                ->get('pagination.default.page');

        $limit = $limit ?? $this->get('danilovl.parameter')
                ->get('pagination.default.limit');

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

#### 2.2 Twig extension

Check `debug` parameter in templates.

```twig
{# templates/first.html.twig #}

{% if parameter_has('debug') == true %}
    {#some code#}
{% endif %}

{% if parameter_get('locale') == 'en' %}
    {#some code#}
{% endif %}
```

Get `google api` parameters.

```twig
{# templates/first.html.twig #}

{{ parameter_get('google.api_key') }}
{{ parameter_get('google.analytics_code') }}
```

## License

The ParameterBundle is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).