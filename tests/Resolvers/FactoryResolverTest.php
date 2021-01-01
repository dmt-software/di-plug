<?php

namespace DMT\Test\DependencyInjection\Resolvers;

use DMT\DependencyInjection\Resolvers\FactoryResolver;
use DMT\Test\DependencyInjection\Fixtures\DummyContainer;
use DMT\Test\DependencyInjection\Fixtures\DummyFactory;
use PHPUnit\Framework\TestCase;

class FactoryResolverTest extends TestCase
{
    public function testResolve()
    {
        $resolver = new FactoryResolver(DummyFactory::class, 'getContainer');

        $this->assertInstanceOf(DummyContainer::class, $resolver->resolve());
    }
}
