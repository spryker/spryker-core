<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\Communication\PluginLocator;
use SprykerEngine\Zed\Kernel\Locator;
use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\AbstractPlugin\FooPlugin;
use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\FooMessenger;

/**
 * @group Kernel
 * @group Business
 * @group AbstractPlugin
 */
class AbstractPluginTest extends \PHPUnit_Framework_TestCase
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
