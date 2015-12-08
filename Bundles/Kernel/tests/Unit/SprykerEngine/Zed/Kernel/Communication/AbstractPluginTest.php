<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException;
use SprykerEngine\Zed\Kernel\AbstractUnitTest;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
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
        $this->setExpectedException(ClassNotFoundException::class);

        $plugin = $this->createPlugin('NonExistentBundle');
        $plugin->getDependencyContainer();
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
        $this->setExpectedException(ClassNotFoundException::class);

        $plugin = $this->createPlugin('NonExistentBundle');
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
        $this->setExpectedException(ClassNotFoundException::class);

        $plugin = $this->createPlugin('NonExistentBundle');
        $plugin->getQueryContainer();
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
     * @param string $bundle
     *
     * @return FooPlugin
     */
    private function createPlugin($bundle = 'Kernel')
    {
        $plugin = $this->getMock(FooPlugin::class, ['getBundleName']);

        $plugin->method('getBundleName')->willReturn($bundle);

        return $plugin;
    }

}
