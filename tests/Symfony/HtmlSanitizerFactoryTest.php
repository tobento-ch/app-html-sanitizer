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
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Tobento\App\HtmlSanitizer\HtmlSanitizerFactoryInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;
use Tobento\App\HtmlSanitizer\Symfony\HtmlSanitizer;
use Tobento\App\HtmlSanitizer\Symfony\HtmlSanitizerFactory;
use Tobento\Service\Container\Container;

class HtmlSanitizerFactoryTest extends TestCase
{
    public function testThatImplementsHtmlSanitizerFactoryInterface()
    {
        $this->assertInstanceof(HtmlSanitizerFactoryInterface::class, new HtmlSanitizerFactory());
    }
    
    public function testCreateSanitizerMethodCreateSanitizer()
    {
        $container = new Container();
        $sanitizer = new HtmlSanitizerFactory()->createSanitizer(name: 'foo', container: $container);
        
        $this->assertInstanceof(HtmlSanitizer::class, $sanitizer);
        $this->assertInstanceof(HtmlSanitizerInterface::class, $sanitizer);
    }
    
    public function testCreateSanitizerMethodWithConfig()
    {
        $container = new Container();
        $config = new HtmlSanitizerConfig();
        $factory = new HtmlSanitizerFactory(
            htmlSanitizerConfig: $config,
        ); 
        
        $sanitizer = $factory->createSanitizer(name: 'foo', container: $container);
                
        $this->assertTrue($config === $factory->htmlSanitizerConfig());
        $this->assertInstanceof(HtmlSanitizer::class, $sanitizer);
    }
}