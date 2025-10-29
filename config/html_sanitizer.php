<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Tobento\App\HtmlSanitizer\HtmlSanitizerInterface;
use Tobento\App\HtmlSanitizer\Purifier;
use Tobento\App\HtmlSanitizer\Symfony;
use function Tobento\App\{directory};

return [
    
    /*
    |--------------------------------------------------------------------------
    | Sanitizers
    |--------------------------------------------------------------------------
    |
    | Configure any sanitizers needed for your application.
    | The first sanitizer is used as the default sanitizer.
    |
    | See: https://github.com/tobento-ch/app-html-sanitizer#available-sanitizers
    |
    */
    
    'sanitizers' => [
        'default' => new Symfony\HtmlSanitizerFactory(
            htmlSanitizerConfig: new HtmlSanitizerConfig()
                ->allowSafeElements()
                ->allowAttribute('class', '*')
                ->allowRelativeLinks(true)
                ->forceAttribute('a', 'rel', 'noopener noreferrer')
        ),
        
        /*'default' => new Purifier\HtmlSanitizerFactory([
            'Cache.SerializerPath' => directory('app').'storage/html-sanitizer/purifier',
            'Cache.SerializerPermissions' => 0755,
            'Attr.AllowedFrameTargets' => ['_blank'],
        ]),*/
        
        // Using a closure:
        /*'another' => static function (string $name): HtmlSanitizerInterface {
            return $createdSanitizer;
        },*/
    ],
];