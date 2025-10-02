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

namespace Tobento\App\HtmlSanitizer\Symfony;

use Psr\Container\ContainerInterface;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer as SymfonyHtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Tobento\App\HtmlSanitizer\HtmlSanitizerFactoryInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;

class HtmlSanitizerFactory implements HtmlSanitizerFactoryInterface
{
    protected HtmlSanitizerConfig $htmlSanitizerConfig;
    
    /**
     * Create a new HtmlSanitizerFactory instance.
     *
     * @param null|HtmlSanitizerConfig $htmlSanitizerConfig
     */
    public function __construct(
        null|HtmlSanitizerConfig $htmlSanitizerConfig = null,
    ) {
        $this->htmlSanitizerConfig = $htmlSanitizerConfig ?: new HtmlSanitizerConfig()->allowSafeElements();
    }

    /**
     * Returns the html sanitizer config.
     *
     * @return HtmlSanitizerConfig
     */
    public function htmlSanitizerConfig(): HtmlSanitizerConfig
    {
        return $this->htmlSanitizerConfig;
    }
    
    /**
     * Returns the sanitizer.
     *
     * @param string $name
     * @param ContainerInterface $container
     * @return HtmlSanitizerInterface
     */
    public function createSanitizer(string $name, ContainerInterface $container): HtmlSanitizerInterface
    {
        return new HtmlSanitizer(
            name: $name,
            htmlSanitizer: new SymfonyHtmlSanitizer($this->htmlSanitizerConfig()),
        );
    }
}