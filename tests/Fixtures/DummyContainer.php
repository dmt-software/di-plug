<?php

namespace DMT\Test\DependencyInjection\Fixtures;

use stdClass;

class DummyContainer
{
    public function get(string $id): object
    {
        return new stdClass();
    }

    public function exists(string $id): bool
    {
        return true;
    }
}