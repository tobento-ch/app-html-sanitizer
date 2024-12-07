# App HTML Sanitizer

App HTML Sanitizer to sanitize untrusted HTML code.

## Table of Contents

- [Getting Started](#getting-started)
    - [Requirements](#requirements)
- [Documentation](#documentation)
    - [App](#app)
    - [Sanitizer Boot](#sanitizer-boot)
        - [Sanitizer Config](#sanitizer-config)
    - [Basic Usage](#basic-usage)
        - [Sanitizing HTML](#sanitizing-html)
        - [Sanitizing HTML in Views](#sanitizing-html-in-views)
        - [Sanitizing HTML using Function](#sanitizing-html-using-function)
    - [Available Sanitizers](#available-sanitizers)
        - [Purifier Sanitizer](#purifier-sanitizer)
    - [Adding Sanitizers](#adding-sanitizers)
- [Credits](#credits)
___

# Getting Started

Add the latest version of the app HTML Sanitizer project running this command.

```
composer require tobento/app-html-sanitizer
```

## Requirements

- PHP 8.0 or greater

# Documentation

## App

Check out the [**App Skeleton**](https://github.com/tobento-ch/app-skeleton) if you are using the skeleton.

You may also check out the [**App**](https://github.com/tobento-ch/app) to learn more about the app in general.

## Sanitizer Boot

The sanitizer boot does the following:

* installs and loads html sanitizer config file
* implements html sanitizer interfaces

```php
use Tobento\App\AppFactory;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizersInterface;

// Create the app
$app = (new AppFactory())->createApp();

// Add directories:
$app->dirs()
    ->dir(realpath(__DIR__.'/../'), 'root')
    ->dir(realpath(__DIR__.'/../app/'), 'app')
    ->dir($app->dir('app').'config', 'config', group: 'config')
    ->dir($app->dir('root').'public', 'public')
    ->dir($app->dir('root').'vendor', 'vendor');

// Adding boots:
$app->boot(\Tobento\App\HtmlSanitizer\Boot\HtmlSanitizer::class);
$app->booting();

// Implemented interfaces:
$htmlSanitizer = $app->get(HtmlSanitizerInterface::class);
$htmlSanitizers = $app->get(HtmlSanitizersInterface::class);

// Run the app
$app->run();
```

### Sanitizer Config

The configuration for the sanitizer is located in the ```app/config/html_sanitizer.php``` file at the default App Skeleton config location where you can configure sanitizers for your application.

## Basic Usage

### Sanitizing HTML

```php
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;

$htmlSanitizer = $app->get(HtmlSanitizerInterface::class);

$safeHtml = $htmlSanitizer->sanitize(html: $html);

$safeHtml = $htmlSanitizer->sanitizeFor(element: 'h1' html: $html);
```

**Using Specific Sanitizer**

```php
use Tobento\App\HtmlSanitizer\HtmlSanitizersInterface;

$htmlSanitizers = $app->get(HtmlSanitizersInterface::class);

$htmlSanitizer = $htmlSanitizers->get(name: 'custom');

$safeHtml = $htmlSanitizer->sanitize(html: $html);

$safeHtml = $htmlSanitizer->sanitizeFor(element: 'h1' html: $html);
```

### Sanitizing HTML in Views

If you have installed the [App View](https://github.com/tobento-ch/app-view), you may use the ```sanitizeHtml``` and ```sanitizeHtmlFor``` view macro to sanitize untrusted HTML:

```php
<!-- Using the default -->
<?= $view->sanitizeHtml($html) ?>

<!-- Or using a specific sanitizer -->
<?= $view->sanitizeHtml(html: $html, sanitizer: 'name') ?>

<!-- Using the default -->
<?= $view->sanitizeHtmlFor('h1', $html, 'named') ?>

<!-- Or using a specific sanitizer -->
<?= $view->sanitizeHtmlFor(element: 'h1', html: $html, sanitizer: 'name') ?>
```

### Sanitizing HTML using Function

```php
use function Tobento\App\HtmlSanitizer\{sanitizeHtml, sanitizeHtmlFor};

$safeHtml = sanitizeHtml($html);
// Or using a specific sanitizer
$safeHtml = sanitizeHtml(html: $html, sanitizer: 'name');

$safeHtml = sanitizeHtmlFor('h1', $html, 'named');
// Or using a specific sanitizer
$safeHtml = sanitizeHtmlFor(element: 'h1', html: $html, sanitizer: 'name');
```

## Available Sanitizers

### Purifier Sanitizer

This HTML sanitizer uses the [Ezyang HTML Purifier](https://github.com/ezyang/htmlpurifier).

In the [Sanitizer Config](#sanitizer-config) file, you can configure this sanitizer using the ```Purifier\HtmlSanitizerFactory::class```:

```php
use Tobento\App\HtmlSanitizer\Purifier;
use function Tobento\App\{directory};

return [
    'sanitizers' => [
        'default' => new Purifier\HtmlSanitizerFactory([
            'Cache.SerializerPath' => directory('app').'storage/html-sanitizer/purifier',
            'Cache.SerializerPermissions' => 0755,
            'Attr.AllowedFrameTargets' => ['_blank'],
        ]),
    ],
];
```

Visit the [Ezyang HTML Purifier](https://github.com/ezyang/htmlpurifier) for more information.

## Adding Sanitizers

In addition to adding sanitizers in the [Sanitizer Config](#sanitizer-config) file, you may adding them using a boot:

```php
use Tobento\App\Boot;
use Tobento\App\HtmlSanitizer\HtmlSanitizerFactoryInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizersInterface;

class HtmlSanitizersBoot extends Boot
{
    public const BOOT = [
        // you may ensure the sanitizer boot.
        \Tobento\App\HtmlSanitizer\Boot\HtmlSanitizer::class,
    ];
    
    public function boot()
    {
        // you may use the app on method to add only if requested:
        $app->on(
            HtmlSanitizersInterface::class,
            static function(HtmlSanitizersInterface $htmlSanitizers) {
                $htmlSanitizers->add(
                    name: 'custom',
                    sanitizer: $sanitizer, // HtmlSanitizerFactoryInterface|HtmlSanitizerInterface
                );
            }
        );
    }
}
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)