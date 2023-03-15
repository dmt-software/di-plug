<?php

namespace DMT\Test\DependencyInjection\Detectors;

use DMT\DependencyInjection\Detectors\CallbackDetector;
use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use DMT\DependencyInjection\Resolvers\ResolverInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class CallbackDetectorTest extends TestCase
{
    public function testDetectFromInstance()
    {
        $detector = new CallbackDetector(
            fn($container) => match (true) {
                $container instanceof stdClass => new Resolver($container),
                $container === null => new ClassResolver(stdClass::class),
                default => null
            }
        );

        $this->assertInstanceOf(Resolver::class, $detector->detect(new stdClass()));
    }

    public function testAutoDetect()
    {
        $detector = new CallbackDetector(
            fn($container) => match (true) {
                $container instanceof stdClass => new Resolver($container),
                $container === null => new ClassResolver(stdClass::class),
                default => null
            }
        );

        $this->assertInstanceOf(ClassResolver::class, $detector->detect(null));
    }

    public function testNothingDetected()
    {
        $detector = new CallbackDetector(
            fn($container) => match (true) {
                $container instanceof stdClass => new Resolver($container),
                $container === null => new ClassResolver(stdClass::class),
                default => null
            }
        );

        $this->assertNull($detector->detect($this));
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreateFromCallable()
    {
        function detect($container)
        {
            if (is_object($container)) {
                return new Resolver($container);
            }
            return null;
        }

        $detector = new CallbackDetector('\DMT\Test\DependencyInjection\Detectors\detect');

        $this->assertInstanceOf(Resolver::class, $detector->detect(new stdClass()));
        $this->assertNull($detector->detect(null));
    }
}
