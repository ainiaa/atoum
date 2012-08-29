<?php

namespace mageekguy\atoum\tests\units\score;

use
	mageekguy\atoum,
	mageekguy\atoum\mock,
	mageekguy\atoum\score
;

require_once __DIR__ . '/../../runner.php';

class coverage extends atoum\test
{
	public function testClass()
	{
		$this->testedClass
			->hasInterface('countable')
			->hasInterface('serializable')
		;
	}

	public function test__construct()
	{
		$this
			->if($coverage = new score\coverage())
			->then
				->variable($coverage->getValue())->isNull()
				->array($coverage->getMethods())->isEmpty()
				->object($coverage->getDependencies())->isInstanceOf('mageekguy\atoum\dependencies')
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->then
				->variable($coverage->getValue())->isNull()
				->array($coverage->getMethods())->isEmpty()
				->object($coverage->getDependencies())->isIdenticalTo($dependencies)
		;
	}

	public function testAddXdebugDataForTest()
	{
		$this
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->then
				->object($coverage->addXdebugDataForTest($this, array()))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
			->if($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($class = new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->isAbstract = false)
			->and($methodController->getName = function() use (& $methodName) { return $methodName; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getName = function() use (& $methodName) { return $methodName; })
			->and($methodController->getStartLine = 6)
			->and($methodController->getEndLine = 8)
			->and($methodController->getFileName = $classFile)
			->and($classController->getMethods = array(new \mock\reflectionMethod(uniqid(), uniqid(), $methodController)))
			->and($dependencies['reflection\class'] = $class)
			->and($classDirectory = uniqid())
			->and($classFile = $classDirectory . DIRECTORY_SEPARATOR . uniqid())
			->and($className = uniqid())
			->and($methodName = uniqid())
			->and($xdebugData = array(
					  $classFile =>
						 array(
							5 => -1,
							6 => 1,
							7 => -1,
							8 => -2,
							9 => -1
						),
					  uniqid() =>
						 array(
							5 => 2,
							6 => 3,
							7 => 4,
							8 => 3,
							9 => 2
						)
					)
				)
			->then
				->object($coverage->addXdebugDataForTest($this, $xdebugData))->isIdenticalTo($coverage)
				->array($coverage->getMethods())->isEqualTo(array(
						$className => array(
							$methodName => array(
								6 => 1,
								7 => -1,
								8 => -2
							)
						)
					)
				)
				->array($coverage->getMethods())->isEqualTo(array(
						$className => array(
							$methodName => array(
								6 => 1,
								7 => -1,
								8 => -2
							)
						)
					)
				)
				->object($coverage->addXdebugDataForTest($this, $xdebugData))->isIdenticalTo($coverage)
				->array($coverage->getMethods())->isEqualTo(array(
						$className => array(
							$methodName => array(
								6 => 1,
								7 => -1,
								8 => -2
							)
						)
					)
				)
			->if($coverage = new score\coverage())
			->and($coverage->excludeClass($this->getTestedClassName()))
			->then
				->object($coverage->addXdebugDataForTest($this, array()))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
				->object($coverage->addXdebugDataForTest($this, $xdebugData))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->and($coverage->excludeDirectory($classDirectory))
			->and($dependencies['reflection\class'] = $class)
			->then
				->object($coverage->addXdebugDataForTest($this, array()))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
				->object($coverage->addXdebugDataForTest($this, $xdebugData))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
		;
	}

	public function testReset()
	{
		$this
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->then
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
				->array($coverage->getExcludedClasses())->isEmpty()
				->array($coverage->getExcludedNamespaces())->isEmpty()
				->array($coverage->getExcludedDirectories())->isEmpty()
				->object($coverage->reset())->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
				->array($coverage->getExcludedClasses())->isEmpty()
				->array($coverage->getExcludedNamespaces())->isEmpty()
				->array($coverage->getExcludedDirectories())->isEmpty()
			->if($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($classController->getStartLine = 1)
			->and($classController->getEndLine = 12)
			->and($class = new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->getName = function() use (& $methodName) { return $methodName; })
			->and($methodController->isAbstract = false)
			->and($methodController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getStartLine = 6)
			->and($methodController->getEndLine = 8)
			->and($classController->getMethods = array(new \mock\reflectionMethod(uniqid(), uniqid(), $methodController)))
			->and($dependencies['reflection\class'] = $class)
			->and($classFile = uniqid())
			->and($className = uniqid())
			->and($methodName = uniqid())
			->and($xdebugData = array(
				  $classFile =>
					 array(
						5 => 1,
						6 => 2,
						7 => 3,
						8 => 2,
						9 => 1
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($coverage->addXdebugDataForTest($this, $xdebugData))
			->and($coverage->excludeClass($excludedClass =uniqid()))
			->and($coverage->excludeNamespace($excludedNamespace= uniqid()))
			->and($coverage->excludeDirectory($excludedDirectory = uniqid()))
			->then
				->array($coverage->getClasses())->isNotEmpty()
				->array($coverage->getMethods())->isNotEmpty()
				->array($coverage->getExcludedClasses())->isNotEmpty()
				->array($coverage->getExcludedNamespaces())->isNotEmpty()
				->array($coverage->getExcludedDirectories())->isNotEmpty()
				->object($coverage->reset())->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
				->array($coverage->getExcludedClasses())->isNotEmpty()
				->array($coverage->getExcludedNamespaces())->isNotEmpty()
				->array($coverage->getExcludedDirectories())->isNotEmpty()
		;
	}

	public function testResetExcludedClasses()
	{
		$this
			->if($coverage = new score\coverage())
			->then
				->object($coverage->resetExcludedClasses())->isIdenticalTo($coverage)
				->array($coverage->getExcludedClasses())->isEmpty()
			->if($coverage->excludeClass(uniqid()))
			->then
				->object($coverage->resetExcludedClasses())->isIdenticalTo($coverage)
				->array($coverage->getExcludedClasses())->isEmpty()
		;
	}

	public function testResetExcludedNamespaces()
	{
		$this
			->if($coverage = new score\coverage())
			->then
				->object($coverage->resetExcludedNamespaces())->isIdenticalTo($coverage)
				->array($coverage->getExcludedNamespaces())->isEmpty()
			->if($coverage->excludeNamespace(uniqid()))
			->then
				->object($coverage->resetExcludedNamespaces())->isIdenticalTo($coverage)
				->array($coverage->getExcludedNamespaces())->isEmpty()
		;
	}

	public function testResetExcludedDirectories()
	{
		$this
			->if($coverage = new score\coverage())
			->then
				->object($coverage->resetExcludedDirectories())->isIdenticalTo($coverage)
				->array($coverage->getExcludedDirectories())->isEmpty()
			->if($coverage->excludeDirectory(uniqid()))
			->then
				->object($coverage->resetExcludedDirectories())->isIdenticalTo($coverage)
				->array($coverage->getExcludedDirectories())->isEmpty()
		;
	}

	public function testMerge()
	{
		$this
			->if($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($classController->getStartLine = 1)
			->and($classController->getEndLine = 12)
			->and($class = new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->getName = function() use (& $methodName) { return $methodName; })
			->and($methodController->isAbstract = false)
			->and($methodController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getStartLine = 6)
			->and($methodController->getEndLine = 8)
			->and($method = new \mock\reflectionMethod(uniqid(), uniqid(), $methodController))
			->and($classController->getMethod = function() use ($method) { return $method; })
			->and($classController->getMethods = array($method))
			->and($classFile = uniqid())
			->and($className = uniqid())
			->and($methodName = uniqid())
			->and($xdebugData = array(
				  $classFile =>
					 array(
						5 => -2,
						6 => -1,
						7 => 1,
						8 => -2,
						9 =>-2
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->and($dependencies['reflection\class'] = $class)
			->then
				->object($coverage->merge($coverage))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
			->if($otherCoverage = new score\coverage($otherDependencies = new atoum\dependencies()))
			->then
				->object($coverage->merge($otherCoverage))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
			->if($coverage->addXdebugDataForTest($this, $xdebugData))
			->then
				->object($coverage->merge($otherCoverage))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEqualTo(array($className => $classFile))
				->array($coverage->getMethods())->isEqualTo(array(
						$className => array(
							$methodName => array(
								6 => -1,
								7 => 1,
								8 => -2
							)
						)
					)
				)
				->object($coverage->merge($coverage))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEqualTo(array($className => $classFile))
				->array($coverage->getMethods())->isEqualTo(array(
						$className => array(
							$methodName => array(
								6 => -1,
								7 => 1,
								8 => -2
							)
						)
					)
				)
			->if($otherClassController = new mock\controller())
			->and($otherClassController->__construct = function() {})
			->and($otherClassController->getName = function() use (& $otherClassName) { return $otherClassName; })
			->and($otherClassController->getFileName = function() use (& $otherClassFile) { return $otherClassFile; })
			->and($otherClassController->getTraits = array())
			->and($otherClassController->getStartLine = 1)
			->and($otherClassController->getEndLine = 12)
			->and($otherClass = new \mock\reflectionClass($class, $otherClassController))
			->and($otherMethodController = new mock\controller())
			->and($otherMethodController->__construct = function() {})
			->and($otherMethodController->getName = function() use (& $otherMethodName) { return $otherMethodName; })
			->and($otherMethodController->isAbstract = false)
			->and($otherMethodController->getFileName = function() use (& $otherClassFile) { return $otherClassFile; })
			->and($otherMethodController->getDeclaringClass = function() use ($otherClass) { return $otherClass; })
			->and($otherMethodController->getStartLine = 5)
			->and($otherMethodController->getEndLine = 9)
			->and($otherClassController->getMethods = array(new \mock\reflectionMethod(uniqid(), uniqid(), $otherMethodController)))
			->and($otherClassFile = uniqid())
			->and($otherClassName = uniqid())
			->and($otherMethodName = uniqid())
			->and($otherXdebugData = array(
				  $otherClassFile =>
					 array(
						1 => -2,
						2 => -1,
						3 => 1,
						4 => 1,
						5 => -1,
						6 => 1,
						7 => 1,
						8 => -1,
						9 => -2,
						10 => 1
					),
				  uniqid() =>
					 array(
						500 => 200,
						600 => 300,
						700 => 400,
						800 => 300,
						900 => 200
					)
				)
			)
			->and($otherDependencies['reflection\class'] = $otherClass)
			->then
				->object($coverage->merge($otherCoverage->addXdebugDataForTest($this, $otherXdebugData)))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEqualTo(array(
						$className => $classFile,
						$otherClassName => $otherClassFile
					)
				)
				->array($coverage->getMethods())->isEqualTo(array(
						$className => array(
							$methodName => array(
								6 => -1,
								7 => 1,
								8 =>-2
							)
						),
						$otherClassName => array(
							$otherMethodName => array(
								5 => -1,
								6 => 1,
								7 => 1,
								8 => -1,
								9 => -2
							)
						)
					)
				)
			->if($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($classController->getStartLine = 1)
			->and($classController->getEndLine = 12)
			->and($class = new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->getName = function() use (& $methodName) { return $methodName; })
			->and($methodController->isAbstract = false)
			->and($methodController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getStartLine = 6)
			->and($methodController->getEndLine = 8)
			->and($method = new \mock\reflectionMethod(uniqid(), uniqid(), $methodController))
			->and($classController->getMethod = function() use ($method) { return $method; })
			->and($classController->getMethods = array($method))
			->and($classFile = uniqid())
			->and($className = uniqid())
			->and($methodName = uniqid())
			->and($xdebugData = array(
				  $classFile =>
					 array(
						5 => -2,
						6 => -1,
						7 => 1,
						8 => -2,
						9 =>-2
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->and($coverage->excludeClass($className))
			->and($dependencies['reflection\class'] = $class)
			->and($otherCoverage = new score\coverage($otherDependencies = new atoum\dependencies()))
			->and($otherDependencies['reflection\class'] = $class)
			->and($otherCoverage->addXdebugDataForTest($this, $xdebugData))
			->then
				->array($otherCoverage->getClasses())->isNotEmpty()
				->array($otherCoverage->getMethods())->isNotEmpty()
				->object($coverage->merge($otherCoverage))->isIdenticalTo($coverage)
				->array($coverage->getClasses())->isEmpty()
				->array($coverage->getMethods())->isEmpty()
		;
	}

	public function testCount()
	{
		$this
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->then
				->sizeOf($coverage)->isZero()
			->if($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($classController->getStartLine = 1)
			->and($classController->getEndLine = 12)
			->and($class = new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->getName = function() use (& $methodName) { return $methodName; })
			->and($methodController->isAbstract = false)
			->and($methodController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getStartLine = 6)
			->and($methodController->getEndLine = 8)
			->and($classController->getMethods = array(new \mock\reflectionMethod(uniqid(), uniqid(), $methodController)))
			->and($dependencies['reflection\class'] = $class)
			->and($classFile = uniqid())
			->and($className = uniqid())
			->and($methodName = uniqid())
			->and($xdebugData = array(
				$classFile =>
					 array(
						5 => 1,
						6 => 2,
						7 => 3,
						8 => 2,
						9 => 1
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->then
				->sizeOf($coverage->addXdebugDataForTest($this, $xdebugData))->isEqualTo(1)
		;
	}

	public function testGetClasses()
	{
		$this
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->and($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($classController->getStartLine = 1)
			->and($classController->getEndLine = 12)
			->and($class = new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->getName = function() { return uniqid(); })
			->and($methodController->isAbstract = false)
			->and($methodController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getStartLine = 4)
			->and($methodController->getEndLine = 8)
			->and($classController->getMethods = array(new \mock\reflectionMethod(uniqid(), uniqid(), $methodController)))
			->and($classFile = uniqid())
			->and($className = uniqid())
			->and($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => -1,
						5 => -1,
						6 => -1,
						7 => -1,
						8 => -2,
						9 => -2
					)
				)
			)
			->and($dependencies['reflection\class'] = $class)
			->and($coverage->addXdebugDataForTest($this, $xdebugData))
			->then
				->array($coverage->getClasses())->isEqualTo(array($className => $classFile))
		;
	}

	public function testGetValue()
	{
		$this
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->and($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($classController->getStartLine = 1)
			->and($classController->getEndLine = 12)
			->and($class = new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->getName = function() { return uniqid(); })
			->and($methodController->isAbstract = false)
			->and($methodController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getStartLine = 4)
			->and($methodController->getEndLine = 8)
			->and($classController->getMethods = array(new \mock\reflectionMethod(uniqid(), uniqid(), $methodController)))
			->and($classFile = uniqid())
			->and($className = uniqid())
			->and($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => -1,
						5 => -1,
						6 => -1,
						7 => -1,
						8 => -2,
						9 => -2
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($dependencies['reflection\class'] = $class)
			->and($coverage->addXdebugDataForTest($this, $xdebugData))
			->then
				->float($coverage->getValue())->isEqualTo(0.0)
			->if($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => 1,
						5 => -1,
						6 => -1,
						7 => -1,
						8 => -2,
						9 => -1
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($coverage->reset()->addXdebugDataForTest($this, $xdebugData))
			->then
				->float($coverage->getValue())->isEqualTo(1 / 4)
			->if($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => 1,
						5 => -1,
						6 => -1,
						7 => 1,
						8 => -2,
						9 => -1
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
			)
		)
		->and($coverage->reset()->addXdebugDataForTest($this, $xdebugData))
		->then
			->float($coverage->getValue())->isEqualTo(2 / 4)
		->if($xdebugData = array(
			  $classFile =>
				 array(
					3 => -2,
					4 => 1,
					5 => 1,
					6 => 1,
					7 => 1,
					8 => -2,
					9 => -1
				),
			  uniqid() =>
				 array(
					5 => 2,
					6 => 3,
					7 => 4,
					8 => 3,
					9 => 2
				)
			)
		)
		->and($coverage->reset()->addXdebugDataForTest($this, $xdebugData))
		->then
			->float($coverage->getValue())->isEqualTo(1.0)
		;
	}

	public function testGetValueForClass()
	{
		$this
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->then
				->variable($coverage->getValueForClass(uniqid()))->isNull()
			->if($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($classController->getStartLine = 1)
			->and($classController->getEndLine = 12)
			->and($class =  new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->getName = function() { return uniqid(); })
			->and($methodController->isAbstract = false)
			->and($methodController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getStartLine = 4)
			->and($methodController->getEndLine = 8)
			->and($classController->getMethods = array(new \mock\reflectionMethod(uniqid(), uniqid(), $methodController)))
			->and($classFile = uniqid())
			->and($className = uniqid())
			->and($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => -1,
						5 => -1,
						6 => -1,
						7 => -1,
						8 => -2,
						9 => -2
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($dependencies['reflection\class'] = $class)
			->and($coverage->addXdebugDataForTest($this, $xdebugData))
			->then
				->variable($coverage->getValueForClass(uniqid()))->isNull()
				->float($coverage->getValueForClass($className))->isEqualTo(0.0)
			->if($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => 1,
						5 => -1,
						6 => -1,
						7 => -1,
						8 => -2,
						9 => -1
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($coverage->reset()->addXdebugDataForTest($this, $xdebugData))
			->then
				->variable($coverage->getValueForClass(uniqid()))->isNull()
				->float($coverage->getValueForClass($className))->isEqualTo(1 / 4)
			->if($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => 1,
						5 => -1,
						6 => -1,
						7 => 1,
						8 => -2,
						9 => -1
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($coverage->reset()->addXdebugDataForTest($this, $xdebugData))
			->then
				->variable($coverage->getValueForClass(uniqid()))->isNull()
				->float($coverage->getValueForClass($className))->isEqualTo(2 / 4)
			->if($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => 1,
						5 => 1,
						6 => 1,
						7 => 1,
						8 => -2,
						9 => -1
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($coverage->reset()->addXdebugDataForTest($this, $xdebugData))
			->then
				->variable($coverage->getValueForClass(uniqid()))->isNull()
				->float($coverage->getValueForClass($className))->isEqualTo(1.0)
		;
	}

	public function testGetCoverageForClass()
	{
		$this
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->then
				->array($coverage->getCoverageForClass(uniqid()))->isEmpty()
			->if($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($classController->getStartLine = 1)
			->and($classController->getEndLine = 12)
			->and($class =  new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->getName = function() use (& $methodName) { return $methodName; })
			->and($methodController->isAbstract = false)
			->and($methodController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getStartLine = 4)
			->and($methodController->getEndLine = 8)
			->and($classController->getMethods = array(new \mock\reflectionMethod(uniqid(), uniqid(), $methodController)))
			->and($classFile = uniqid())
			->and($className = uniqid())
			->and($methodName = uniqid())
			->and($xdebugData = array(
				$classFile =>
					array(
						3 => -2,
						4 => 1,
						5 => -1,
						6 => -1,
						7 => -1,
						8 => -2,
						9 => -1
					),
					uniqid() =>
					array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($expected = array(
				$methodName =>
					array(
						4 => 1,
						5 => -1,
						6 => -1,
						7 => -1,
						8 => -2,
					)
				)
			)
			->and($dependencies['reflection\class'] = $class)
			->and($coverage->addXdebugDataForTest($this, $xdebugData))
			->then
				->array($coverage->getCoverageForClass($className))->isEqualTo($expected)
		;
	}

	public function testGetValueForMethod()
	{
		$this
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->then
				->variable($coverage->getValueForMethod(uniqid(), uniqid()))->isNull()
			->if($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($classController->getStartLine = 1)
			->and($classController->getEndLine = 12)
			->and($class = new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->getName = function() use (& $methodName) { return $methodName; })
			->and($methodController->isAbstract = false)
			->and($methodController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getStartLine = 4)
			->and($methodController->getEndLine = 8)
			->and($classController->getMethods = array(new \mock\reflectionMethod(uniqid(), uniqid(), $methodController)))
			->and($classFile = uniqid())
			->and($className = uniqid())
			->and($methodName = uniqid())
			->and($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => -1,
						5 => -1,
						6 => -1,
						7 => -1,
						8 => -2,
						9 => -2
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($dependencies['reflection\class'] = $class)
			->and($coverage->addXdebugDataForTest($this, $xdebugData))
			->then
				->variable($coverage->getValueForMethod(uniqid(), uniqid()))->isNull()
				->variable($coverage->getValueForMethod($className, uniqid()))->isNull()
				->float($coverage->getValueForMethod($className, $methodName))->isEqualTo(0.0)
			->if($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => 1,
						5 => -1,
						6 => -1,
						7 => -1,
						8 => -2,
						9 => -1
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($coverage->reset()->addXdebugDataForTest($this, $xdebugData))
			->then
				->variable($coverage->getValueForMethod(uniqid(), uniqid()))->isNull()
				->variable($coverage->getValueForMethod($className, uniqid()))->isNull()
				->float($coverage->getValueForMethod($className, $methodName))->isEqualTo(1 / 4)
			->if($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => 1,
						5 => -1,
						6 => -1,
						7 => 1,
						8 => -2,
						9 => -1
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($coverage->reset()->addXdebugDataForTest($this, $xdebugData))
			->then
				->variable($coverage->getValueForMethod(uniqid(), uniqid()))->isNull()
				->variable($coverage->getValueForMethod($className, uniqid()))->isNull()
				->float($coverage->getValueForMethod($className, $methodName))->isEqualTo(2 / 4)
			->if($xdebugData = array(
				  $classFile =>
					 array(
						3 => -2,
						4 => 1,
						5 => 1,
						6 => 1,
						7 => 1,
						8 => -2,
						9 => -1
					),
				  uniqid() =>
					 array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($coverage->reset()->addXdebugDataForTest($this, $xdebugData))
			->then
				->variable($coverage->getValueForMethod(uniqid(), uniqid()))->isNull()
				->variable($coverage->getValueForMethod($className, uniqid()))->isNull()
				->float($coverage->getValueForMethod($className, $methodName))->isEqualTo(1.0)
		;
	}

	public function testGetCoverageForMethod()
	{
		$this
			->if($coverage = new score\coverage($dependencies = new atoum\dependencies()))
			->then
				->array($coverage->getCoverageForClass(uniqid()))->isEmpty()
			->if($classController = new mock\controller())
			->and($classController->__construct = function() {})
			->and($classController->getName = function() use (& $className) { return $className; })
			->and($classController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($classController->getTraits = array())
			->and($classController->getStartLine = 1)
			->and($classController->getEndLine = 12)
			->and($class =  new \mock\reflectionClass(uniqid(), $classController))
			->and($methodController = new mock\controller())
			->and($methodController->__construct = function() {})
			->and($methodController->getName = function() use (& $methodName) { return $methodName; })
			->and($methodController->isAbstract = false)
			->and($methodController->getFileName = function() use (& $classFile) { return $classFile; })
			->and($methodController->getDeclaringClass = function() use ($class) { return $class; })
			->and($methodController->getStartLine = 4)
			->and($methodController->getEndLine = 8)
			->and($classController->getMethods = array(new \mock\reflectionMethod(uniqid(), uniqid(), $methodController)))
			->and($classFile = uniqid())
			->and($className = uniqid())
			->and($methodName = uniqid())
			->and($xdebugData = array(
				$classFile =>
					array(
						3 => -2,
						4 => 1,
						5 => -1,
						6 => -1,
						7 => -1,
						8 => -2,
						9 => -1
					),
					uniqid() =>
					array(
						5 => 2,
						6 => 3,
						7 => 4,
						8 => 3,
						9 => 2
					)
				)
			)
			->and($expected = array(
				4 => 1,
				5 => -1,
				6 => -1,
				7 => -1,
				8 => -2,
			))
			->and($dependencies['reflection\class'] = $class)
			->and($coverage->addXdebugDataForTest($this, $xdebugData))
			->then
				->array($coverage->getCoverageForMethod($className, $methodName))->isEqualTo($expected)
		;
	}

	public function testExcludeClass()
	{
		$this
			->if($coverage = new score\coverage())
			->then
				->object($coverage->excludeClass($class = uniqid()))->isIdenticalTo($coverage)
				->array($coverage->getExcludedClasses())->isEqualTo(array($class))
				->object($coverage->excludeClass($otherClass = rand(1, PHP_INT_MAX)))->isIdenticalTo($coverage)
				->array($coverage->getExcludedClasses())->isEqualTo(array($class, (string) $otherClass))
				->object($coverage->excludeClass($class))->isIdenticalTo($coverage)
				->array($coverage->getExcludedClasses())->isEqualTo(array($class, (string) $otherClass))
		;
	}

	public function testExcludeNamespace()
	{
		$this
			->if($coverage = new score\coverage())
			->then
				->object($coverage->excludeNamespace($namespace = uniqid()))->isIdenticalTo($coverage)
				->array($coverage->getExcludedNamespaces())->isEqualTo(array($namespace))
				->object($coverage->excludeNamespace($otherNamespace = rand(1, PHP_INT_MAX)))->isIdenticalTo($coverage)
				->array($coverage->getExcludedNamespaces())->isEqualTo(array($namespace, (string) $otherNamespace))
				->object($coverage->excludeNamespace($namespace))->isIdenticalTo($coverage)
				->array($coverage->getExcludedNamespaces())->isEqualTo(array($namespace, (string) $otherNamespace))
				->object($coverage->excludeNamespace('\\' . ($anotherNamespace = uniqid()) . '\\'))->isIdenticalTo($coverage)
				->array($coverage->getExcludedNamespaces())->isEqualTo(array($namespace, (string) $otherNamespace, $anotherNamespace))
		;
	}

	public function testExcludeDirectory()
	{
		$this
			->if($coverage = new score\coverage())
			->then
				->object($coverage->excludeDirectory($directory = uniqid()))->isIdenticalTo($coverage)
				->array($coverage->getExcludedDirectories())->isEqualTo(array($directory))
				->object($coverage->excludeDirectory($otherDirectory = rand(1, PHP_INT_MAX)))->isIdenticalTo($coverage)
				->array($coverage->getExcludedDirectories())->isEqualTo(array($directory, (string) $otherDirectory))
				->object($coverage->excludeDirectory($directory))->isIdenticalTo($coverage)
				->array($coverage->getExcludedDirectories())->isEqualTo(array($directory, (string) $otherDirectory))
				->object($coverage->excludeDirectory(($anotherDirectory = (DIRECTORY_SEPARATOR . uniqid())) . DIRECTORY_SEPARATOR))->isIdenticalTo($coverage)
				->array($coverage->getExcludedDirectories())->isEqualTo(array($directory, (string) $otherDirectory, $anotherDirectory))
		;
	}

	public function testIsInExcludedClasses()
	{
		$this
			->if($coverage = new score\coverage())
			->then
				->boolean($coverage->isInExcludedClasses(uniqid()))->isFalse()
			->if($coverage->excludeClass($class = uniqid()))
			->then
				->boolean($coverage->isInExcludedClasses(uniqid()))->isFalse()
				->boolean($coverage->isInExcludedClasses($class))->isTrue()
		;
	}

	public function testIsInExcludedNamespaces()
	{
		$this
			->if($coverage = new score\coverage())
			->then
				->boolean($coverage->isInExcludedNamespaces(uniqid()))->isFalse()
			->if($coverage->excludeNamespace($namespace = uniqid()))
			->then
				->boolean($coverage->isInExcludedNamespaces(uniqid()))->isFalse()
				->boolean($coverage->isInExcludedNamespaces($namespace . '\\' . uniqid()))->isTrue()
		;
	}

	public function testIsInExcludedDirectories()
	{
		$this
			->if($coverage = new score\coverage())
			->then
				->boolean($coverage->isInExcludedDirectories(uniqid()))->isFalse()
			->if($coverage->excludeDirectory($directory = uniqid()))
			->then
				->boolean($coverage->isInExcludedDirectories(uniqid()))->isFalse()
				->boolean($coverage->isInExcludedDirectories($directory . DIRECTORY_SEPARATOR . uniqid()))->isTrue()
				->boolean($coverage->isInExcludedDirectories($directory . uniqid() . DIRECTORY_SEPARATOR . uniqid()))->isFalse()
		;
	}
}
