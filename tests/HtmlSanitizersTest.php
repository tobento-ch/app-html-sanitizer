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

namespace Tobento\App\HtmlSanitizer\Test;

use PHPUnit\Framework\TestCase;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizers;
use Tobento\App\HtmlSanitizer\HtmlSanitizersInterface;
use Tobento\App\HtmlSanitizer\Purifier\HtmlSanitizerFactory;
use Tobento\Service\Container\Container;

class HtmlSanitizersTest extends TestCase
{
    public function testThatImplementsHtmlSanitizersInterface()
    {
        $container = new Container();
        $sanitizers = new HtmlSanitizers(container: $container, sanitizers: []);
        
        $this->assertInstanceof(HtmlSanitizersInterface::class, $sanitizers);
    }
    
    public function testAddMethodWithSanitizer()
    {
        $container = new Container();
        $sanitizers = new HtmlSanitizers(container: $container, sanitizers: []);
        
        $this->assertFalse($sanitizers->has('foo'));
        $this->assertSame([], $sanitizers->names());
        
        $sanitizer = (new HtmlSanitizerFactory())->createSanitizer(name: 'foo', container: $container);
        
        $sanitizers->add('foo', $sanitizer);
        
        $this->assertTrue($sanitizers->has('foo'));
        $this->assertSame($sanitizer, $sanitizers->get('foo'));
        $this->assertSame(['foo'], $sanitizers->names());
    }
    
    public function testAddMethodWithSanitizerFactory()
    {
        $container = new Container();
        $sanitizers = new HtmlSanitizers(container: $container, sanitizers: []);
        
        $this->assertFalse($sanitizers->has('foo'));
        $this->assertSame([], $sanitizers->names());
        
        $sanitizer = new HtmlSanitizerFactory();
        
        $sanitizers->add('foo', $sanitizer);
        
        $this->assertTrue($sanitizers->has('foo'));
        $this->assertInstanceof(HtmlSanitizerInterface::class, $sanitizers->get('foo'));
        $this->assertSame(['foo'], $sanitizers->names());
    }
    
    public function testConstructSanitizers()
    {
        $container = new Container();
        $sanitizers = new HtmlSanitizers(container: $container, sanitizers: [
            'foo' => new HtmlSanitizerFactory(),
            'bar' => (new HtmlSanitizerFactory())->createSanitizer(name: 'foo', container: $container),
            'baz' => static function (string $name) use ($container): HtmlSanitizerInterface {
                return (new HtmlSanitizerFactory())->createSanitizer(name: 'foo', container: $container);
            },
        ]);
        
        $this->assertTrue($sanitizers->has('foo'));
        $this->assertTrue($sanitizers->has('bar'));
        $this->assertTrue($sanitizers->has('baz'));
        $this->assertFalse($sanitizers->has('null'));
        $this->assertSame(['foo', 'bar', 'baz'], $sanitizers->names());
        $this->assertInstanceof(HtmlSanitizerInterface::class, $sanitizers->get('foo'));
        $this->assertInstanceof(HtmlSanitizerInterface::class, $sanitizers->get('bar'));
        $this->assertInstanceof(HtmlSanitizerInterface::class, $sanitizers->get('baz'));
    }
    
    public function testGetMethodReturnsFirstSanitizerIfNotExist()
    {
        $container = new Container();
        $sanitizers = new HtmlSanitizers(container: $container, sanitizers: [
            'foo' => new HtmlSanitizerFactory(),
        ]);
        
        $this->assertInstanceof(HtmlSanitizerInterface::class, $sanitizers->get('bar'));
    }
    
    public function testGetMethodThrowsLogicExceptionIfNoSanitizersExists()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('At least one html sanitizer is required');
        
        $container = new Container();
        $sanitizers = new HtmlSanitizers(container: $container, sanitizers: []);
        $sanitizers->get('bar');
    }
}