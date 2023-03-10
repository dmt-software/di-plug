<?php

declare(strict_types=1);

namespace DMT\Test\DependencyInjection\Resolvers;

use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\Test\DependencyInjection\Fixtures\DummyContainer;
use PHPUnit\Framework\TestCase;

class ClassResolverTest extends TestCase
{
    public function testResolve()
    {
        $resolver = new ClassResolver(DummyContainer::class);

        $this->assertInstanceOf(DummyContainer::class, $resolver->resolve());
    }
}
