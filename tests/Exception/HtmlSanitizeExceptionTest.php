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

namespace Tobento\App\HtmlSanitizer\Test\Exception;

use PHPUnit\Framework\TestCase;
use Tobento\App\HtmlSanitizer\Exception\HtmlSanitizeException;
use Tobento\App\HtmlSanitizer\Exception\HtmlSanitizerException;

class HtmlSanitizeExceptionTest extends TestCase
{
    public function testException()
    {
        $this->assertInstanceof(HtmlSanitizerException::class, new HtmlSanitizeException());
    }
}