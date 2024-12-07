<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);
 
namespace Tobento\App\HtmlSanitizer\Boot;

use Psr\Container\ContainerInterface;
use Tobento\App\Boot;
use Tobento\App\Boot\Config;
use Tobento\App\Boot\Functions;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizers;
use Tobento\App\HtmlSanitizer\HtmlSanitizersInterface;
use Tobento\App\Migration\Boot\Migration;
use Tobento\Service\View\ViewInterface;

/**
 * HtmlSanitizer boot
 */
class HtmlSanitizer extends Boot
{
    public const INFO = [
        'boot' => [
            'installs and loads html sanitizer config file',
            'implements html sanitizer interfaces',
        ],
    ];

    public const BOOT = [
        Config::class,
        Migration::class,
        Functions::class,
    ];

    /**
     * Boot application services.
     *
     * @param Migration $migration
     * @param Config $config
     * @param Functions $functions
     * @return void
     */
    public function boot(Migration $migration, Config $config, Functions $functions): void
    {
        // install migration:
        $migration->install(\Tobento\App\HtmlSanitizer\Migration\HtmlSanitizer::class);
        
        // interfaces:
        $this->app->set(
            HtmlSanitizersInterface::class,
            static function(ContainerInterface $container) use ($config): HtmlSanitizersInterface {
                $config = $config->load(file: 'html_sanitizer.php');
                
                return new HtmlSanitizers(
                    $container,
                    $config['sanitizers'] ?? []
                );
            }
        );
        
        $this->app->set(
            HtmlSanitizerInterface::class,
            static function(HtmlSanitizersInterface $sanitizers): HtmlSanitizerInterface {
                return $sanitizers->get();
            }
        );
        
        // view macro:
        $this->app->on(
            ViewInterface::class,
            function(ViewInterface $view) {
                $view->addMacro('sanitizeHtml', [$this, 'sanitize']);
                $view->addMacro('sanitizeHtmlFor', [$this, 'sanitizeFor']);
            }
        );
        
        // Functions:
        $functions->register(__DIR__.'/../functions.php');
    }
    
    /**
     * Sanitizes an untrusted HTML.
     * This method is NOT context sensitive.
     *
     * @param string $html
     * @param null|string $sanitizer
     * @return string
     */
    public function sanitize(string $html, null|string $sanitizer = null): string
    {
        $sanitizers = $this->app->get(HtmlSanitizersInterface::class);
        
        return $sanitizers->get($sanitizer)->sanitize($html);
    }
    
    /**
     * Sanitizes an untrusted HTML.
     * This method is context sensitive.
     *
     * @param string $element
     * @param string $html
     * @param null|string $sanitizer
     * @return string
     */
    public function sanitizeFor(string $element, string $html, null|string $sanitizer = null): string
    {
        $sanitizers = $this->app->get(HtmlSanitizersInterface::class);
        
        return $sanitizers->get($sanitizer)->sanitizeFor($element, $html);
    }
}