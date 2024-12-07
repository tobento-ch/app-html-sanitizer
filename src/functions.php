<?php

/**
 * TOBENTO
 *
 * @copyright    Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\App\HtmlSanitizer;

use Psr\Container\ContainerInterface;
use Tobento\Service\HelperFunction\Functions;
use Tobento\App\HtmlSanitizer\HtmlSanitizersInterface;

if (!function_exists(__NAMESPACE__.'\sanitizeHtml')) {
    /**
     * Sanitizes an untrusted HTML.
     * This method is NOT context sensitive.
     *
     * @param string $html
     * @param null|string $sanitizer
     * @return string
     */
    function sanitizeHtml(string $html, null|string $sanitizer = null): string
    {
        $sanitizers = Functions::get(ContainerInterface::class)->get(HtmlSanitizersInterface::class);
        
        return $sanitizers->get($sanitizer)->sanitize($html);
    }
}
if (!function_exists(__NAMESPACE__.'\sanitizeHtmlFor')) {
    /**
     * Sanitizes an untrusted HTML.
     * This method is context sensitive.
     *
     * @param string $element
     * @param string $html
     * @param null|string $sanitizer
     * @return string
     */
    function sanitizeHtmlFor(string $element, string $html, null|string $sanitizer = null): string
    {
        $sanitizers = Functions::get(ContainerInterface::class)->get(HtmlSanitizersInterface::class);
        
        return $sanitizers->get($sanitizer)->sanitizeFor($element, $html);
    }
}