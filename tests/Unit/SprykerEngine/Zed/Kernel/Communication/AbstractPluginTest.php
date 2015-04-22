<?php

namespace Unit\SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\Communication\PluginLocator;
use SprykerEngine\Zed\Kernel\Locator;

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
}
