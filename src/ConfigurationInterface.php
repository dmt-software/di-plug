<?php

namespace DMT\DependencyInjection;

/**
 * An implementing class registered in the container may resolve
 * constructor arguments defined by ConfigValue attribute.
 *
 * @example
 *
 * public function __construct(#[ConfigValue('foo', 'bar')]) {}
 *
 * Instanciating the class tries to resolve an entry called foo in
 * the Configuration object as constructor argument if omitted.
 */
interface ConfigurationInterface
{
    /**
     * Get a value from configuration.
     */
    public function get(string $value, mixed $default = null): mixed;
}
