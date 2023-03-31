# Updating & fixing with original author link and my updated version soon.
> **Orignal:** Calotype / SEO

[![Latest Stable Version](https://poser.pugx.org/calotype/seo/version.png)](https://packagist.org/packages/calotype/seo) [![Total Downloads](https://poser.pugx.org/calotype/seo/d/total.png)](https://packagist.org/packages/calotype/seo) [![Build Status](https://travis-ci.org/Calotype/SEO.png)](https://travis-ci.org/Calotype/SEO)

> **Duplicate:** Tacnix / SEO

## Tacnix / SEO

> **Warning!** This package is still in an alpha/beta state.

SEO is a package that provides helpers for some common SEO techniques.

## Features

- __Meta tags__:
    - Set the meta title and description on the fly.
    - Set the meta title and description by object.

- __Sitemap.xml__:
    - Generate a sitemap.xml and add your own entries.
    - Generate entries from one or more objects (lazy-loading is available).

- __Robots.txt__:
    - Generate a robots.txt and add your own entries.

## Installation

### Composer / Packagist

Require the package in your `composer.json`.

```
"calotype/seo": "dev-master"
```

Run composer install or update to download the package.

```bash
$ composer update
```

## Providers

### Laravel 4

Just register the service provider and the facades in `app/config/app.php` and you are good to go.

```php
// Service provider
'Tacnix\SEO\Providers\SEOServiceProvider',

// Facades (can customize if preferred)
'SEO\Meta'    => 'Tacnix\SEO\Facades\Meta',
'SEO\Sitemap' => 'Tacnix\SEO\Facades\Sitemap',
'SEO\Robots'  => 'Tacnix\SEO\Facades\Robots',
```


