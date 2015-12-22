<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Kernel\AbstractUnitTest;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\ClassInfo;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Unit\Spryker\Zed\Kernel\Communication\Fixtures\AbstractPlugin\Plugin\FooPlugin;

/**
 * @group Spryker
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
    public function testGetCommunicationFactoryShouldReturnInstanceIfExists()
    {
        $plugin = new FooPlugin();

        $pluginReflection = new \ReflectionClass($plugin);
        $communicationFactoryProperty = $pluginReflection->getParentClass()->getProperty('factory');
        $communicationFactoryProperty->setAccessible(true);
        $communicationFactoryProperty->setValue($plugin, $this->getMock(AbstractCommunicationFactory::class, null, [], '', false));

        $communicationFactory = $plugin->getFactory();

        $this->assertInstanceOf(AbstractCommunicationFactory::class, $communicationFactory);
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
