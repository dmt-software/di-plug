<?php

namespace DMT\Test\DependencyInjection\Detectors;

use DI\Container;
use DMT\DependencyInjection\Detectors\PhpDiDetector;
use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use PHPUnit\Framework\TestCase;
use stdClass;

class PhpDiDetectorTest extends TestCase
{
    public function testDetectFromInstance()
    {
        $container = new Container();
        $detector = new PhpDiDetector();

        $this->assertInstanceOf(Resolver::class, $detector->detect($container));
    }

    public function testAutoDetect()
    {
        $detector = new PhpDiDetector();

        $this->assertInstanceOf(ClassResolver::class, $detector->detect(null));
    }

    public function testNothingDetected()
    {
        $detector = new PhpDiDetector();

        $this->assertNull($detector->detect(new stdClass()));
    }
}
