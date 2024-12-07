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

/**
 * HtmlSanitizersInterface
 */
interface HtmlSanitizersInterface
{
    /**
     * Add a sanitizer.
     *
     * @param string $name
     * @param HtmlSanitizerInterface|HtmlSanitizerFactoryInterface $sanitizer
     * @return static $this
     */
    public function add(string $name, HtmlSanitizerInterface|HtmlSanitizerFactoryInterface $sanitizer): static;
    
    /**
     * Returns true if sanitizer exists, otherwise false.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;
    
    /**
     * Returns a sanitizer by name.
     *
     * @param null|string $name
     * @return HtmlSanitizerInterface
     */
    public function get(null|string $name = null): HtmlSanitizerInterface;
    
    /**
     * Returns all sanitizer names.
     *
     * @return array<int, string>
     */
    public function names(): array;
}