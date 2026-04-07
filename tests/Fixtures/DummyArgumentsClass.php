<?php

namespace DMT\Test\DependencyInjection\Fixtures;

class DummyArgumentsClass
{
    public function __construct(
        public string $requiredArgument,
        public ?string $nullableArgument = null
    ) {
    }
}
