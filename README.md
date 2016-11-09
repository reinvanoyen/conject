# Conject

### Usage

#### Default (retreive shared instance each time)
```php
$container = new Container();

$container->set('DependingClass', function () use ($container) {
    return new DependingClass($c->get('SomeDependency'));
});

$container->set('SomeDependency', function () use ($container) {
    return new SomeDependency();
});

// Get instance of DependingClass (with SomeDependency injected!)
$depending = $container->get('DependingClass');
$depending2 = $container->get('DependingClass');

var_dump( $depending === $depending2 ); // true
```

#### Factory (retreive new instance each time)
```php
$container = new Container();

$container->factory('DependingClass', function () use ($container) {
    return new DependingClass($c->get('SomeDependency'));
});

$container->factory('SomeDependency', function () use ($container) {
    return new SomeDependency();
});

// Get instance of DependingClass (with SomeDependency injected!)
$depending = $container->get('DependingClass');
$depending2 = $container->get('DependingClass');

var_dump( $depending === $depending2 ); // false
```