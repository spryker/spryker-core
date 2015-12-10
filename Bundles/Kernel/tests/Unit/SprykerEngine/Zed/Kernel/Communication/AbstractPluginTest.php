<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\AbstractUnitTest;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\ClassResolver\ClassInfo;
use SprykerEngine\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerNotFoundException;
use SprykerEngine\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerResolver;
use SprykerEngine\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use SprykerEngine\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use SprykerEngine\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\AbstractPlugin\Plugin\FooPlugin;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Kernel
 * @group Communication
 * @group AbstractPlugin
 */
class AbstractPluginTest extends AbstractUnitTest
{

    /**
     * @return void
     */
    public function testGetDependencyContainerShouldThrowExceptionIfDependencyContainerNotFound()
    {
        $this->setExpectedException(DependencyContainerNotFoundException::class);

        $dependencyContainerResolverMock = $this->getMock(DependencyContainerResolver::class, ['canResolve', 'getClassInfo']);
        $dependencyContainerResolverMock->method('canResolve')->willReturn(false);

        $classInfo = new ClassInfo();
        $classInfo->setClass('\\Namespace\\Application\\Bundle\\Layer\\Foo\\Bar');
        $dependencyContainerResolverMock->method('getClassInfo')->willReturn($classInfo);

        $pluginMock = $this->getPluginMock(['getDependencyContainerResolver']);
        $pluginMock->method('getDependencyContainerResolver')->willReturn($dependencyContainerResolverMock);

        $pluginMock->getDependencyContainer();
    }

    /**
     * @return void
     */
    public function testGetDependencyContainerShouldReturnInstanceIfExists()
    {
        $plugin = new FooPlugin();

        $pluginReflection = new \ReflectionClass($plugin);
        $dependencyContainerProperty = $pluginReflection->getParentClass()->getProperty('dependencyContainer');
        $dependencyContainerProperty->setAccessible(true);
        $dependencyContainerProperty->setValue($plugin, $this->getMock(AbstractCommunicationDependencyContainer::class, null, [], '', false));

        $dependencyContainer = $plugin->getDependencyContainer();

        $this->assertInstanceOf(AbstractCommunicationDependencyContainer::class, $dependencyContainer);
    }

    /**
     * @return void
     */
    public function testGetFacadeShouldThrowExceptionIfFacadeNotFound()
    {
        $this->setExpectedException(FacadeNotFoundException::class);

        $plugin = new FooPlugin();
        $plugin->getFacade();
    }

    /**
     * @return void
     */
    public function testGetFacadeShouldReturnInstanceIfExists()
    {
        $plugin = new FooPlugin();

        $pluginReflection = new \ReflectionClass($plugin);
        $facadeProperty = $pluginReflection->getParentClass()->getProperty('facade');
        $facadeProperty->setAccessible(true);
        $facadeProperty->setValue($plugin, $this->getMock(AbstractFacade::class, null, [], '', false));

        $facade = $plugin->getFacade();

        $this->assertInstanceOf(AbstractFacade::class, $facade);
    }

    /**
     * @return void
     */
    public function testGetQueryContainerThrowExceptionIfQueryContainerNotFound()
    {
        $this->setExpectedException(QueryContainerNotFoundException::class);

        $queryContainerResolverMock = $this->getMock(QueryContainerResolver::class, ['canResolve', 'getClassInfo']);
        $queryContainerResolverMock->method('canResolve')->willReturn(false);

        $classInfo = new ClassInfo();
        $classInfo->setClass('\\Namespace\\Application\\Bundle\\Layer\\Foo\\Bar');
        $queryContainerResolverMock->method('getClassInfo')->willReturn($classInfo);

        $pluginMock = $this->getPluginMock(['getQueryContainerResolver']);
        $pluginMock->method('getQueryContainerResolver')->willReturn($queryContainerResolverMock);

        $pluginMock->getQueryContainer();
    }

    /**
     * @return void
     */
    public function testGetQueryContainerShouldReturnInstanceIfQueryContainerIfExists()
    {
        $plugin = new FooPlugin();

        $pluginReflection = new \ReflectionClass($plugin);
        $queryContainerProperty = $pluginReflection->getParentClass()->getProperty('queryContainer');
        $queryContainerProperty->setAccessible(true);
        $queryContainerProperty->setValue($plugin, $this->getMock(AbstractQueryContainer::class, null, [], '', false));

        $queryContainer = $plugin->getQueryContainer();

        $this->assertInstanceOf(AbstractQueryContainer::class, $queryContainer);
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|FooPlugin
     */
    protected function getPluginMock(array $methods)
    {
        $pluginMock = $this->getMock(FooPlugin::class, $methods);

        return $pluginMock;
    }

}
