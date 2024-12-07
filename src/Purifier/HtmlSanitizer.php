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
use Tobento\App\HtmlSanitizer\Exception\HtmlSanitizeException;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;

/**
 * HtmlSanitizer
 */
class HtmlSanitizer implements HtmlSanitizerInterface
{
    /**
     * Create a new HtmlSanitizer instance.
     *
     * @param string $name
     * @param HTMLPurifier $purifier
     */
    public function __construct(
        protected string $name,
        protected HTMLPurifier $purifier,
    ) {}
    
    /**
     * Returns the purifier.
     *
     * @return HTMLPurifier
     */
    public function purifier(): HTMLPurifier
    {
        return $this->purifier;
    }
    
    /**
     * Returns the sanitizer name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
    
    /**
     * Sanitizes an untrusted HTML.
     * This method is NOT context sensitive.
     *
     * @param string $html
     * @return string
     */
    public function sanitize(string $html): string
    {
        return $this->purifier->purify($html);
    }
    
    /**
     * Sanitizes an untrusted HTML.
     * This method is context sensitive.
     *
     * @param string $element
     * @param string $html
     * @return string
     */
    public function sanitizeFor(string $element, string $html): string
    {
        throw new HtmlSanitizeException('Not supported');
    }
}