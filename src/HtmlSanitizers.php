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

namespace Tobento\App\HtmlSanitizer;

use Psr\Container\ContainerInterface;
use Tobento\Service\Autowire\Autowire;
use LogicException;

/**
 * HtmlSanitizers
 */
class HtmlSanitizers implements HtmlSanitizersInterface
{
    /**
     * @var Autowire
     */
    protected Autowire $autowire;
    
    /**
     * Create a new HtmlSanitizers.
     *
     * @param ContainerInterface $container
     * @param array $sanitizers
     */
    public function __construct(
        ContainerInterface $container,
        protected array $sanitizers,
    ) {
        $this->autowire = new Autowire($container);
    }
    
    /**
     * Add a sanitizer.
     *
     * @param string $name
     * @param HtmlSanitizerInterface|HtmlSanitizerFactoryInterface $sanitizer
     * @return static $this
     */
    public function add(string $name, HtmlSanitizerInterface|HtmlSanitizerFactoryInterface $sanitizer): static
    {
        $this->sanitizers[$name] = $sanitizer;
        return $this;
    }
    
    /**
     * Returns true if sanitizer exists, otherwise false.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->sanitizers);
    }
    
    /**
     * Returns a sanitizer.
     *
     * @param null|string $name
     * @return HtmlSanitizerInterface
     */
    public function get(null|string $name = null): HtmlSanitizerInterface
    {
        if (is_null($name)) {
            $name = $this->getFirstSanitizerName();
        }
        
        if (!isset($this->sanitizers[$name])) {
            $name = $this->getFirstSanitizerName();
        }
        
        if ($this->sanitizers[$name] instanceof HtmlSanitizerInterface) {
            return $this->sanitizers[$name];
        }
        
        // create sanitizer from callable:
        if (is_callable($this->sanitizers[$name])) {
            return $this->sanitizers[$name] = $this->autowire->call(
                $this->sanitizers[$name],
                ['name' => $name]
            );
        }

        // create sanitizer from factory:
        if ($this->sanitizers[$name] instanceof HtmlSanitizerFactoryInterface) {
            return $this->sanitizers[$name] = $this->sanitizers[$name]->createSanitizer(
                name: $name,
                container: $this->autowire->container(),
            );
        }
        
        throw new LogicException('Unable to create html sanitizer');
    }
    
    /**
     * Returns all sanitizer names.
     *
     * @return array<int, string>
     */
    public function names(): array
    {
        return array_keys($this->sanitizers);
    }
    
    /**
     * Returns the first sanitizer name.
     *
     * @return string
     */
    protected function getFirstSanitizerName(): string
    {
        $name = array_key_first($this->sanitizers);
        
        if (is_string($name)) {
            return $name;
        }
        
        throw new LogicException('At least one html sanitizer is required');
    }
}