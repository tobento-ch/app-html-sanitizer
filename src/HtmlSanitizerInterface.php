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

use Stringable;

/**
 * HtmlSanitizerInterface
 */
interface HtmlSanitizerInterface
{
    /**
     * Returns the sanitizer name.
     *
     * @return string
     */
    public function name(): string;
    
    /**
     * Sanitizes an untrusted HTML.
     * This method is NOT context sensitive.
     *
     * @param string|Stringable $html
     * @return string
     */
    public function sanitize(string|Stringable $html): string;
    
    /**
     * Sanitizes an untrusted HTML.
     * This method is context sensitive.
     *
     * @param string $element
     * @param string|Stringable $html
     * @return string
     */
    public function sanitizeFor(string $element, string|Stringable $html): string;
}