# DI-Plug

## Introduction
[psr-11](https://www.php-fig.org/psr/psr-11/) introduced a standardized way for packages, libraries and frameworks to
retrieve objects from containers. This package also allows adding dependencies to a container by a uniform interface.
Its goal is to help package developers to access the dependency container to make installation more easy.

## Installation
```bash
composer require dmt-software/di-plug
```
## Usage

### Manual set a container
```php
use DMT\DependencyInjection\Container;
use Pimple\Pimple;
 
$containeer = new Container(new Pimple());
$containeer->set(SomeClass::class, function () {
    return new SomeClass($this->get(SomeDependency::class));
});
````

### Autodetect a container
```php
use DMT\DependencyInjection\ContainerFactory;
 
/** @var object|null $fromContainerInstanceOrNull */
$factory = new ContainerFactory();
$container = $factory->createContainer($fromContainerInstanceOrNull);
$container->set(SomeClass::class, function () {
    return new SomeClass($this->get(SomeDependency::class));
});
````

## Supports
 - [Aura dependency injection container](https://packagist.org/packages/aura/di)
 - [Pimple container](https://packagist.org/packages/pimple/pimple)