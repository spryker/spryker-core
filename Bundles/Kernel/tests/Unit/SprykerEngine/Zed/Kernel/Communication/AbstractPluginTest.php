<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\AbstractUnitTest;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Communication\PluginLocator;
use SprykerEngine\Zed\Kernel\Locator;
use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\AbstractPlugin\FooPlugin;
use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\FooMessenger;
use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\PluginLocator\Facade;
use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\PluginLocator\LocatorMock;
use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\PluginLocator\Plugin\Foo;
use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\PluginLocator\QueryContainer;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Kernel
 * @group Communication
 * @group AbstractPlugin
 */
class AbstractPluginTest extends AbstractUnitTest
{

    public function testCreateInstanceShouldInjectDependencyContainerIfOneExists()
    {
        $locator = new PluginLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Communication\\Fixtures\\PluginLocator\\Factory'
        );
        $locatedClass = $locator->locate('Kernel', Locator::getInstance(), 'Foo');

        $this->assertInstanceOf(
            'Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\PluginLocator\Plugin\Foo',
            $locatedClass
        );
    }

    public function testGetQueryContainerShouldReturnNullIfNoQueryContainerIsSet()
    {
        $locator = new PluginLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Communication\\Fixtures\\PluginLocator\\Factory'
        );
        $plugin = $locator->locate('Kernel', Locator::getInstance(), 'Foo');
        $queryContainer = $plugin->getQueryContainerForTests();

        $this->assertNull($queryContainer);
    }

    public function testGetQueryContainerShouldReturnInstanceIfQueryContainerIsSet()
    {
        $plugin = new Foo(new Factory('Kernel'), Locator::getInstance());
        $plugin->setOwnQueryContainer(
            new QueryContainer(new \SprykerEngine\Zed\Kernel\Persistence\Factory('Kernel'), Locator::getInstance())
        );

        $queryContainer = $plugin->getQueryContainerForTests();

        $this->assertInstanceOf('SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer', $queryContainer);
    }

    public function testGetFacadeShouldReturnInstanceIfFacadeIsSet()
    {
        $plugin = new Foo(new Factory('Kernel'), Locator::getInstance());
        $plugin->setOwnFacade(
            new Facade(new \SprykerEngine\Zed\Kernel\Persistence\Factory('Kernel'), Locator::getInstance())
        );

        $facade = $plugin->getFacadeForTests();

        $this->assertInstanceOf('SprykerEngine\Zed\Kernel\Business\AbstractFacade', $facade);
    }

    public function testSetMessenger()
    {
        $locator = new PluginLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Communication\\Fixtures\\PluginLocator\\Factory'
        );
        /** @var FooPlugin $plugin */
        $plugin = $locator->locate('Kernel', Locator::getInstance(), 'Foo');

        $messengerMock = new FooMessenger();
        $plugin->setMessenger($messengerMock);
        $plugin->log('warning', 'foo', ['key' => 'value']);
        $return = $messengerMock->getLogMock();
        $this->assertEquals('warning', $return['level']);
        $this->assertEquals('foo', $return['message']);
        $this->assertTrue(is_array($return['context']));
        $this->arrayHasKey('key', $return['context']);
        $this->assertEquals('value', $return['context']['key']);
    }
}
