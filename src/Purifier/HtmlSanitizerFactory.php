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

namespace Tobento\App\HtmlSanitizer\Purifier;

use HTMLPurifier;
use HTMLPurifier_Config;
use Psr\Container\ContainerInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizerFactoryInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;

/**
 * HtmlSanitizerFactory
 */
class HtmlSanitizerFactory implements HtmlSanitizerFactoryInterface
{
    /**
     * Create a new HtmlSanitizerFactory instance.
     *
     * @param array $config
     */
    public function __construct(
        protected array $config = [],
    ) {}
    
    /**
     * Returns the sanitizer.
     *
     * @param string $name
     * @param ContainerInterface $container
     * @return HtmlSanitizerInterface
     */
    public function createSanitizer(string $name, ContainerInterface $container): HtmlSanitizerInterface
    {
        $config = HTMLPurifier_Config::createDefault();
        
        if (!empty($this->config)) {
            $config->loadArray($this->config);
        }

        return new HtmlSanitizer(name: $name, purifier: new HTMLPurifier($config));
    }
}