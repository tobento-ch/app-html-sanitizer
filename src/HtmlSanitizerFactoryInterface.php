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

namespace Tobento\App\HtmlSanitizer;

use Psr\Container\ContainerInterface;

/**
 * HtmlSanitizerFactoryInterface
 */
interface HtmlSanitizerFactoryInterface
{
    /**
     * Returns the sanitizer.
     *
     * @param string $name
     * @param ContainerInterface $container
     * @return HtmlSanitizerInterface
     */
    public function createSanitizer(string $name, ContainerInterface $container): HtmlSanitizerInterface;
}