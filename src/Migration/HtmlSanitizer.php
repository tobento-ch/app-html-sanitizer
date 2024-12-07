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

namespace Tobento\App\HtmlSanitizer\Migration;

use Tobento\Service\Filesystem\Dir;
use Tobento\Service\Migration\Actions;
use Tobento\Service\Migration\ActionsInterface;
use Tobento\Service\Migration\Action\CallableAction;
use Tobento\Service\Migration\Action\FilesCopy;
use Tobento\Service\Migration\Action\FilesDelete;
use Tobento\Service\Migration\MigrationInterface;
use Tobento\Service\Dir\DirsInterface;

/**
 * HtmlSanitizer migration.
 */
class HtmlSanitizer implements MigrationInterface
{
    /**
     * @var array The files.
     */
    protected array $files;
    
    /**
     * Create a new HtmlSanitizer instance.
     *
     * @param DirsInterface $dirs
     */
    public function __construct(
        protected DirsInterface $dirs,
    ) {
        $vendor = realpath(__DIR__.'/../../');
        
        $this->files = [
            $this->dirs->get('config') => [
                $vendor.'/config/html_sanitizer.php',
            ],
        ];
    }
    
    /**
     * Return a description of the migration.
     *
     * @return string
     */
    public function description(): string
    {
        return 'Html sanitizer.';
    }
        
    /**
     * Return the actions to be processed on install.
     *
     * @return ActionsInterface
     */
    public function install(): ActionsInterface
    {
        return new Actions(
            new FilesCopy(
                files: $this->files,
                type: 'config',
                description: 'Html sanitizer config file.',
            ),
            new CallableAction(
                callable: function () {
                    (new Dir())->create(
                        directory: $this->dirs->get('app').'storage/html-sanitizer/purifier/',
                        mode: 0755,
                        recursive: true
                    );
                },
                description: 'Html sanitizer cache directory.',
                type: 'directory',
            ),
        );
    }

    /**
     * Return the actions to be processed on uninstall.
     *
     * @return ActionsInterface
     */
    public function uninstall(): ActionsInterface
    {
        return new Actions(
            new FilesDelete(
                files: $this->files,
                type: 'config',
                description: 'Html sanitizer config file.',
            ),
            new CallableAction(
                callable: function () {
                    (new Dir())->delete($this->dirs->get('app').'storage/html-sanitizer/purifier/');
                },
                description: 'Html sanitizer cache directory.',
                type: 'directory',
            ),
        );
    }
}