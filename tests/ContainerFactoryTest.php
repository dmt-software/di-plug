<?php

declare(strict_types=1);

namespace DMT\Test\DependencyInjection;

use DMT\DependencyInjection\Adapters\AuraAdapter;
use DMT\DependencyInjection\Adapters\PhpDiAdapter;
use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\Container;
use DMT\DependencyInjection\ContainerFactory;
use DMT\DependencyInjection\Detectors\InstalledClassDetector;
use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\FactoryResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use DMT\Test\DependencyInjection\Fixtures\DummyAdapter;
use DMT\Test\DependencyInjection\Fixtures\DummyContainer;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ContainerFactoryTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testCreateContainerFromInstance()
    {
        $this->assertFalse(class_exists(PhpDiAdapter::class, false));

        $factory = new ContainerFactory();
        $container = $factory->createContainer(new \DI\Container());

        $this->assertInstanceOf(Container::class, $container);
        $this->assertTrue(class_exists(PhpDiAdapter::class, false));
    }

    /**
     * @runInSeparateProcess
     */
    public function testAutodetectContainer()
    {
        $this->assertFalse(class_exists(PimpleAdapter::class, false));

        $factory = new ContainerFactory([
            'supported' => [
                \Pimple\Container::class => [
                    'adapter' => PimpleAdapter::class,
                    'resolver' => Resolver::class,
                ]
            ],
            'detectors' => [
                InstalledClassDetector::class => [
                    'supported' => [
                        \Pimple\Container::class,
                    ]
                ]
            ],
        ]);
        $container = $factory->createContainer();

        $this->assertInstanceOf(Container::class, $container);
        $this->assertTrue(class_exists(PimpleAdapter::class, false));
    }

    /**
     * @runInSeparateProcess
     */
    public function testAutodetectFromFactory()
    {
        $this->assertFalse(class_exists(AuraAdapter::class, false));

        $factory = new ContainerFactory([
            'supported' => [
                \Aura\Di\ContainerBuilder::class => [
                    'adapter' => AuraAdapter::class,
                    'resolver' => FactoryResolver::class,
                    'accessor' => 'newInstance',
                ]
            ],
            'detectors' => [
                InstalledClassDetector::class => [
                    'supported' => [
                        \Aura\Di\ContainerBuilder::class,
                    ]
                ]
            ],
        ]);
        $container = $factory->createContainer();

        $this->assertInstanceOf(Container::class, $container);
        $this->assertTrue(class_exists(AuraAdapter::class, false));
    }

    /**
     * @dataProvider provideUnsupportedContainer
     */
    public function testUnsupportedContainer(?array $configuration, ?object $container = null)
    {
        $this->expectExceptionObject(new RuntimeException('Unsupported container'));

        (new ContainerFactory($configuration))->createContainer($container);
    }

    public function provideUnsupportedContainer(): iterable
    {
        return [
            'object not in configuration' => [[], new \Pimple\Container()],
            'auto discover class not found' => [
                [
                    'support' => [
                        DummyContainer::class => [
                            'adapter' => DummyAdapter::class,
                            'resolver' => ClassResolver::class,
                        ]
                    ],
                    'detectors' => [
                        InstalledClassDetector::class => [
                            DummyContainer::class,
                        ]
                    ]
                ],
            ],
        ];
    }
}
