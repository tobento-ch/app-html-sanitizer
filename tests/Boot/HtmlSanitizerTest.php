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

namespace Tobento\App\HtmlSanitizer\Test\Boot;

use PHPUnit\Framework\TestCase;
use Tobento\App\AppFactory;
use Tobento\App\AppInterface;
use Tobento\App\HtmlSanitizer\Boot\HtmlSanitizer;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;
use Tobento\App\HtmlSanitizer\HtmlSanitizersInterface;
use Tobento\Service\View\ViewInterface;
use Tobento\Service\Filesystem\Dir;
use function Tobento\App\HtmlSanitizer\{sanitizeHtml, sanitizeHtmlFor};

class HtmlSanitizerTest extends TestCase
{
    protected function createApp(bool $deleteDir = true): AppInterface
    {
        if ($deleteDir) {
            (new Dir())->delete(__DIR__.'/../app/');
        }
        
        (new Dir())->create(__DIR__.'/../app/');
        
        $app = (new AppFactory())->createApp();
        
        $app->dirs()
            ->dir(realpath(__DIR__.'/../../'), 'root')
            ->dir(realpath(__DIR__.'/../app/'), 'app')
            ->dir($app->dir('app').'config', 'config', group: 'config')
            ->dir($app->dir('root').'vendor', 'vendor')
            // for testing only we add public within app dir.
            ->dir($app->dir('app').'public', 'public');
        
        return $app;
    }
    
    public static function tearDownAfterClass(): void
    {
        (new Dir())->delete(__DIR__.'/../app/');
    }
    
    public function testInterfacesAreAvailable()
    {
        $app = $this->createApp();
        $app->boot(HtmlSanitizer::class);
        $app->booting();
        
        $this->assertInstanceof(HtmlSanitizerInterface::class, $app->get(HtmlSanitizerInterface::class));
        $this->assertInstanceof(HtmlSanitizersInterface::class, $app->get(HtmlSanitizersInterface::class));
    }
    
    public function testSanitizeHtmlViewMacro()
    {
        $app = $this->createApp();
        $app->boot(HtmlSanitizer::class);
        $app->boot(\Tobento\App\View\Boot\View::class);
        $app->booting();
        $view = $app->get(ViewInterface::class);
        
        $this->assertTrue($view->hasMacro(name: 'sanitizeHtml'));
        
        $this->assertSame(
            '<p>lorem</p>',
            $view->sanitizeHtml(html: '<p>lorem<script>alert(1)</script></p>')
        );
        
        $this->assertSame(
            '<p>lorem</p>',
            $view->sanitizeHtml(html: '<p>lorem<script>alert(1)</script></p>', sanitizer: 'foo')
        );
    }
    
    public function testSanitizeHtmlForViewMacro()
    {
        $app = $this->createApp();
        $app->boot(HtmlSanitizer::class);
        $app->boot(\Tobento\App\View\Boot\View::class);
        $app->booting();
        $view = $app->get(ViewInterface::class);
        
        $this->assertTrue($view->hasMacro(name: 'sanitizeHtmlFor'));
    }
    
    public function testSanitizeHtmlFunction()
    {
        $app = $this->createApp();
        $app->boot(HtmlSanitizer::class);
        $app->boot(\Tobento\App\View\Boot\View::class);
        $app->booting();

        $this->assertSame(
            '<p>lorem</p>',
            sanitizeHtml(html: '<p>lorem<script>alert(1)</script></p>')
        );
        
        $this->assertSame(
            '<p>lorem</p>',
            sanitizeHtml(html: '<p>lorem<script>alert(1)</script></p>', sanitizer: 'foo')
        );
    }
    
    public function testSanitizeHtmlForFunction()
    {
        $app = $this->createApp();
        $app->boot(HtmlSanitizer::class);
        $app->boot(\Tobento\App\View\Boot\View::class);
        $app->booting();
        
        $this->assertTrue(function_exists('\Tobento\App\HtmlSanitizer\sanitizeHtmlFor'));
    }
}