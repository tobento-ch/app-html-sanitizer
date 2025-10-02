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

use Stringable;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer as SymfonyHtmlSanitizer;
use Tobento\App\HtmlSanitizer\Exception\HtmlSanitizeException;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;

class HtmlSanitizer implements HtmlSanitizerInterface
{
    /**
     * Create a new HtmlSanitizer instance.
     *
     * @param string $name
     * @param SymfonyHtmlSanitizer $htmlSanitizer
     */
    public function __construct(
        protected string $name,
        protected SymfonyHtmlSanitizer $htmlSanitizer,
    ) {}
    
    /**
     * Returns the html sanitizer.
     *
     * @return SymfonyHtmlSanitizer
     */
    public function htmlSanitizer(): SymfonyHtmlSanitizer
    {
        return $this->htmlSanitizer;
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
     * @param string|Stringable $html
     * @return string
     */
    public function sanitize(string|Stringable $html): string
    {
        return $this->htmlSanitizer->sanitize((string)$html);
    }
    
    /**
     * Sanitizes an untrusted HTML.
     * This method is context sensitive.
     *
     * @param string $element
     * @param string|Stringable $html
     * @return string
     */
    public function sanitizeFor(string $element, string|Stringable $html): string
    {
        return $this->htmlSanitizer->sanitizeFor($element, (string)$html);
    }
}