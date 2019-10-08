<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Communication;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Shared\Kernel\ClassResolver\ClassInfo;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerTest\Zed\Kernel\Communication\Fixtures\AbstractPlugin\Plugin\FooPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Communication
 * @group AbstractPluginTest
 * Add your own group annotations below this line
 */
class AbstractPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testGetCommunicationFactoryShouldReturnInstanceIfExists()
    {
        $plugin = new FooPlugin();

        $pluginReflection = new ReflectionClass($plugin);
        $communicationFactoryProperty = $pluginReflection->getParentClass()->getProperty('factory');
        $communicationFactoryProperty->setAccessible(true);
        $abstractCommunicationFactoryMock = $this->getMockBuilder(AbstractCommunicationFactory::class)->disableOriginalConstructor()->getMock();
        $communicationFactoryProperty->setValue($plugin, $abstractCommunicationFactoryMock);

        $communicationFactory = $plugin->getFactory();

        $this->assertInstanceOf(AbstractCommunicationFactory::class, $communicationFactory);
    }

    /**
     * @return void
     */
    public function testGetFacadeShouldThrowExceptionIfFacadeNotFound()
    {
        $this->expectException(FacadeNotFoundException::class);

        $plugin = new FooPlugin();
        $plugin->getFacade();
    }

    /**
     * @return void
     */
    public function testGetFacadeShouldReturnInstanceIfExists()
    {
        $plugin = new FooPlugin();

        $pluginReflection = new ReflectionClass($plugin);
        $facadeProperty = $pluginReflection->getParentClass()->getProperty('facade');
        $facadeProperty->setAccessible(true);
        $abstractFacadeMock = $this->getMockBuilder(AbstractFacade::class)->disableOriginalConstructor()->getMock();
        $facadeProperty->setValue($plugin, $abstractFacadeMock);

        $facade = $plugin->getFacade();

        $this->assertInstanceOf(AbstractFacade::class, $facade);
    }

    /**
     * @return void
     */
    public function testGetQueryContainerThrowExceptionIfQueryContainerNotFound()
    {
        $this->expectException(QueryContainerNotFoundException::class);

        $queryContainerResolverMock = $this->getMockBuilder(QueryContainerResolver::class)->setMethods(['canResolve', 'getClassInfo'])->getMock();
        $queryContainerResolverMock->method('canResolve')->willReturn(false);

        $classInfo = new ClassInfo();
        $classInfo->setClass('\\Namespace\\Application\\Bundle\\Layer\\Foo\\Bar');
        $queryContainerResolverMock->method('getClassInfo')->willReturn($classInfo);

        $fooPlugin = new FooPlugin();

        $fooPluginReflection = new ReflectionClass($fooPlugin);
        $getQueryContainerResolverMethod = $fooPluginReflection->getParentClass()->getMethod('getQueryContainerResolver');
        $getQueryContainerResolverMethod->setAccessible(true);
        $getQueryContainerResolverMethod->invoke($fooPlugin, $queryContainerResolverMock);

        $fooPlugin->getQueryContainer();
    }

    /**
     * @return void
     */
    public function testGetQueryContainerShouldReturnInstanceIfQueryContainerIfExists()
    {
        $plugin = new FooPlugin();

        $pluginReflection = new ReflectionClass($plugin);
        $queryContainerProperty = $pluginReflection->getParentClass()->getProperty('queryContainer');
        $queryContainerProperty->setAccessible(true);
        $queryContainerProperty->setValue($plugin, $this->getMockBuilder(AbstractQueryContainer::class)->disableOriginalConstructor()->getMock());

        $queryContainer = $plugin->getQueryContainer();

        $this->assertInstanceOf(AbstractQueryContainer::class, $queryContainer);
    }
}
