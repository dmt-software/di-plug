<?php

declare(strict_types=1);

namespace DMT\Test\DependencyInjection\Resolvers;

use DMT\DependencyInjection\Resolvers\PropertyResolver;
use DMT\Test\DependencyInjection\Fixtures\DummyContainer;
use DMT\Test\DependencyInjection\Fixtures\DummyPsrContainer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PropertyResolverTest extends TestCase
{
    /**
     * @dataProvider provideContainer
     *
     * @param object $wrapper
     * @param string $property
     * @param object $container
     */
    public function testResolve(object $wrapper, string $property, object $container)
    {
        $resolver = new PropertyResolver($wrapper, $property);
        $this->assertEquals($container, $resolver->resolve());
    }

    public function provideContainer(): iterable
    {
        $dummy = new DummyContainer();

        return [
            [new DummyPsrContainer($dummy), 'container', $dummy],
            [new DummyPsrContainer($dummy), 'publicContainer', $dummy],
        ];
    }

    /**
     * @dataProvider provideContainerFailure
     *
     * @param object $wrapper
     * @param string $property
     */
    public function testResolveFailure(object $wrapper, string $property)
    {
        $this->expectException(InvalidArgumentException::class);
        (new PropertyResolver($wrapper, $property))->resolve();
    }

    public function provideContainerFailure(): iterable
    {
        $dummy = new DummyContainer();

        return [
            [new DummyPsrContainer($dummy), 'missingContainer'],
            [new DummyPsrContainer($dummy), 'containerName'],
        ];
    }
}
