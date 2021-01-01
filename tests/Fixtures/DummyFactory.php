<?php

namespace DMT\Test\DependencyInjection\Fixtures;

class DummyFactory
{
    public function getContainer()
    {
        return new DummyContainer();
    }
}