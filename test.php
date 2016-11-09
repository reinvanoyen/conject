<?php

require __DIR__.'/Container.php';

class SomeDependency
{
    public function __construct()
    {
        $this->value = substr(md5(rand()), 0, 7);
    }
}

class DependingClass
{
    public $dependency;

    public function __construct(SomeDependency $dependency)
    {
        $this->dependency = $dependency;
    }
}

class SomeFactoryDependency
{
    public function __construct()
    {
        $this->value = substr(md5(rand()), 0, 7);
    }
}

class FactoryCreatedClass
{
    public $dependency;

    public function __construct(SomeFactoryDependency $dependency)
    {
        $this->dependency = $dependency;
    }
}

// Begin test

$c = new Container();

// Default test
$c->set('DependingClass', function () use ($c) {
    return new DependingClass($c->get('SomeDependency'));
});

$c->set('SomeDependency', function () use ($c) {
    return new SomeDependency();
});

$simple_class = $c->get('DependingClass');
$simple_class2 = $c->get('DependingClass');
$simple_class3 = $c->get('DependingClass');

var_dump($simple_class === $simple_class2);
var_dump($simple_class->dependency->value.' > '.$simple_class2->dependency->value);

var_dump($simple_class2 === $simple_class3);
var_dump($simple_class2->dependency->value.' > '.$simple_class3->dependency->value);

// Factory test
$c->factory('SomeFactoryDependency', function () use ($c) {
    return new SomeFactoryDependency();
});

$c->factory('FactoryCreatedClass', function () use ($c) {
    return new FactoryCreatedClass($c->get('SomeFactoryDependency'));
});

$factory_class = $c->get('FactoryCreatedClass');
$factory_class2 = $c->get('FactoryCreatedClass');
$factory_class3 = $c->get('FactoryCreatedClass');

var_dump($factory_class === $factory_class2);
var_dump($factory_class->dependency->value.' > '.$factory_class2->dependency->value);
var_dump($factory_class->dependency->value.' > '.$factory_class3->dependency->value);
