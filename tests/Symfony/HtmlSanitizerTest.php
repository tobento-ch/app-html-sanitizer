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

namespace Tobento\App\HtmlSanitizer\Test\Symfony;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer as SymfonyHtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Tobento\App\HtmlSanitizer\Exception\HtmlSanitizeException;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;
use Tobento\App\HtmlSanitizer\Symfony\HtmlSanitizer;
use Tobento\App\HtmlSanitizer\Test\Html;

class HtmlSanitizerTest extends TestCase
{
    protected function createSanitizer(string $name = 'name'): HtmlSanitizer
    {
        return new HtmlSanitizer(
            name: $name,
            htmlSanitizer: new SymfonyHtmlSanitizer(new HtmlSanitizerConfig()->allowSafeElements())
        );
    }
    
    public function testThatImplementsHtmlSanitizerInterface()
    {
        $this->assertInstanceof(HtmlSanitizerInterface::class, $this->createSanitizer());
    }
    
    public function testHtmlSanitizerMethod()
    {
        $symfonySanitizer = new SymfonyHtmlSanitizer(new HtmlSanitizerConfig()->allowSafeElements());
        $sanitizer = new HtmlSanitizer(name: 'foo', htmlSanitizer: $symfonySanitizer);
        
        $this->assertSame($symfonySanitizer, $sanitizer->htmlSanitizer());
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
        
        $this->assertSame('html', $this->createSanitizer()->sanitize(html: new Html()));
    }
    
    public function testSanitizeForMethod()
    {
        $this->assertSame(
            '<p>lorem</p>',
            $this->createSanitizer()->sanitizeFor(element: 'h1', html: '<p>lorem</p>')
        );
    }
}