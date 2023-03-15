<?php

namespace DMT\Test\DependencyInjection\Detectors;

use Aura\Di\ContainerBuilder;
use DMT\DependencyInjection\Detectors\AuraDetector;
use DMT\DependencyInjection\Resolvers\FactoryResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use PHPUnit\Framework\TestCase;
use stdClass;

class AuraDetectorTest extends TestCase
{
    public function testDetectFromInstance()
    {
        $detector = new AuraDetector();
        $builder = new ContainerBuilder();

        $this->assertInstanceOf(Resolver::class, $detector->detect($builder->newInstance()));
    }

    public function testAutoDetect()
    {
        $detector = new AuraDetector();

        $this->assertInstanceOf(FactoryResolver::class, $detector->detect(null));
    }

    public function testNothingDetected()
    {
        $detector = new AuraDetector();

        $this->assertNull($detector->detect(new stdClass()));
    }
}
