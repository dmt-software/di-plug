<?php

declare(strict_types=1);

namespace DMT\Test\DependencyInjection\Fixtures;

class DummyFactory
{
    public function getContainer()
    {
        return new DummyContainer();
    }
}
