<?php

declare(strict_types=1);

namespace DMT\Test\DependencyInjection\Resolvers;

use DMT\DependencyInjection\Resolvers\Resolver;
use DMT\Test\DependencyInjection\Fixtures\DummyContainer;
use PHPUnit\Framework\TestCase;

class ResolverTest extends TestCase
{
    public function testResolve()
    {
        $resolver = new Resolver(new DummyContainer());

        $this->assertInstanceOf(DummyContainer::class, $resolver->resolve());
    }
}
