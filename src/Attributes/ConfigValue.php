<?php

namespace DMT\DependencyInjection\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class ConfigValue
{
    public function __construct(
        public string $entry,
        public mixed $defaultValue = null
    ) {
    }
}