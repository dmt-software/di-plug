<?php

namespace DMT\Test\DependencyInjection\Fixtures;

use DMT\DependencyInjection\Attributes\ConfigValue;

class DummyConfigurableClass
{
    public function __construct(
        #[ConfigValue(entry: 'foo')]
        public string $foo,
        #[ConfigValue(entry: 'bar', defaultValue: 'baz')]
        public ?string $bar = null,
    ) {
    }
}
