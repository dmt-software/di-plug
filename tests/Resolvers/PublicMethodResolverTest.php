<?php

namespace DMT\Test\DependencyInjection\Resolvers;

use DMT\DependencyInjection\Resolvers\PublicMethodResolver;
use DMT\Test\DependencyInjection\Fixtures\DummyContainer;
use DMT\Test\DependencyInjection\Fixtures\DummyPsrContainer;
use PHPUnit\Framework\TestCase;

class PublicMethodResolverTest extends TestCase
{
    public function testResolve()
    {
        $resolver = new PublicMethodResolver(new DummyPsrContainer(new DummyContainer()), 'getInnerContainer');

        $this->assertInstanceOf(DummyContainer::class, $resolver->resolve());
    }
}
