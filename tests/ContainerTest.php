<?php

class Dependency
{
	public $property;

	public function __construct()
	{
		$this->property = 'Property value';
	}
}

class DependingClass
{
	public $dependency;

	public function __construct( Dependency $dependency )
	{
		$this->dependency = $dependency;
	}
}

class ContainerTest extends PHPUnit_Framework_TestCase
{
	public function testGetInstance()
	{
		$container = new Container();

		$container->set('Dependency', function() use ($container) {

			return new Dependency();
		});

		$container->set('DependingClass', function() use ($container) {

			return new DependingClass($container->get('Dependency'));
		});

		$stub = $container->get('DependingClass');
		$stub2 = $container->get('DependingClass');

		$this->assertSame($stub, $stub2);
	}

	public function testGetFactoryInstance()
	{
		$container = new Container();

		$container->factory('Dependency', function() use ($container) {

			return new Dependency();
		});

		$container->factory('DependingClass', function() use ($container) {

			return new DependingClass($container->get('Dependency'));
		});

		$stub = $container->get('DependingClass');
		$stub2 = $container->get('DependingClass');

		$this->assertNotSame($stub, $stub2);
	}

	public function testGetFactoryInstanceDependency()
	{
		$container = new Container();

		$container->set('Dependency', function() use ($container) {

			return new Dependency();
		});

		$container->factory('DependingClass', function() use ($container) {

			return new DependingClass($container->get('Dependency'));
		});

		$stub = $container->get('DependingClass');
		$stub2 = $container->get('DependingClass');

		$this->assertNotSame($stub, $stub2);
		$this->assertSame($stub->dependency, $stub2->dependency);
	}
}