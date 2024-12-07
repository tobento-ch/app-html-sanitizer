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

use PHPUnit\Framework\TestCase;
use Tobento\App\HtmlSanitizer\HtmlSanitizerFactoryInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;
use Tobento\App\HtmlSanitizer\Purifier\HtmlSanitizer;
use Tobento\App\HtmlSanitizer\Purifier\HtmlSanitizerFactory;
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
        $sanitizer = (new HtmlSanitizerFactory())->createSanitizer(name: 'foo', container: $container);
        
        $this->assertInstanceof(HtmlSanitizer::class, $sanitizer);
        $this->assertInstanceof(HtmlSanitizerInterface::class, $sanitizer);
    }
    
    public function testCreateSanitizerMethodWithConfig()
    {
        $container = new Container();
        $sanitizer = (new HtmlSanitizerFactory([
            'Attr.AllowedFrameTargets' => ['_blank'],
        ]))->createSanitizer(name: 'foo', container: $container);
        
        $config = $sanitizer->purifier()->config;
        
        $this->assertSame(['_blank' => true], $config->get('Attr.AllowedFrameTargets'));
    }
}