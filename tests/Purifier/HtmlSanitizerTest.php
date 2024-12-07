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

namespace Tobento\App\HtmlSanitizer\Test\Purifier;

use HTMLPurifier;
use HTMLPurifier_Config;
use PHPUnit\Framework\TestCase;
use Tobento\App\HtmlSanitizer\Exception\HtmlSanitizeException;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;
use Tobento\App\HtmlSanitizer\Purifier\HtmlSanitizer;

class HtmlSanitizerTest extends TestCase
{
    protected function createSanitizer(string $name = 'name'): HtmlSanitizer
    {
        return new HtmlSanitizer(
            name: $name,
            purifier: new HTMLPurifier(HTMLPurifier_Config::createDefault())
        );
    }
    
    public function testThatImplementsHtmlSanitizerInterface()
    {
        $this->assertInstanceof(HtmlSanitizerInterface::class, $this->createSanitizer());
    }
    
    public function testPurifierMethod()
    {
        $purifier = new HTMLPurifier(HTMLPurifier_Config::createDefault());
        $sanitizer = new HtmlSanitizer(name: 'foo', purifier: $purifier);
        
        $this->assertSame($purifier, $sanitizer->purifier());
    }
    
    public function testNameMethod()
    {
        $this->assertSame('foo', $this->createSanitizer(name: 'foo')->name());
    }
    
    public function testSanitizeMethodCleansHtml()
    {
        $this->assertSame(
            '<p>lorem</p>',
            $this->createSanitizer()->sanitize(html: '<p>lorem<script>alert(1)</script></p>')
        );
    }
    
    public function testSanitizeForMethodThrowsHtmlSanitizeExceptionAsNotSupported()
    {
        $this->expectException(HtmlSanitizeException::class);
        $this->expectExceptionMessage('Not supported');
        
        $this->createSanitizer()->sanitizeFor(element: 'h1', html: '<p>lorem</p>');
    }
}