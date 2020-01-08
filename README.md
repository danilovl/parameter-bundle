# GetParameterBundle #

## About ##

Symfony bundle provides comfortable getting parameters from config.

### Requirements 

  * PHP 7.3.0 or higher
  * Symfony 4.4 or higher
  * TwigBundle 4.4 or higher

### 1. Installation

Install `danilovl/get-parameter-bundle` package by Composer:
 
``` bash
$ composer require danilovl/get-parameter-bundle
```
Add the `GetParameterBundle` to your application's bundles if does not add automatically:

``` php
<?php
// config/bundles.php

return [
    // ...
    Danilovl\GetParameterBundle\GetParameterBundle::class => ['all' => true]
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

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\PaginationInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController
{
    /**
     * @param Request $request
     * @param array|Collection|Query $query
     * @param int $page
     * @param int $limit
     * @param array $options
     * @return PaginationInterface
     *
     * @throws LogicException
     */
    protected function createPagination(
        Request $request,
        $query,
        int $page = null,
        int $limit = null,
        array $options = null
    ): PaginationInterface {
        $page = $page ?? $this->get('danilovl.get_parameter')
                ->getParameter('pagination.default.page');

        $limit = $limit ?? $this->get('danilovl.get_parameter')
                ->getParameter('pagination.default.limit');

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

{% if get_parameter('debug') == true %}
    {#some code#}
{% endif%}
```

Get `google api` parameters.

```twig
{# templates/first.html.twig #}

{{ get_parameter('google.api_key') }}
{{ get_parameter('google.analytics_code') }}
```