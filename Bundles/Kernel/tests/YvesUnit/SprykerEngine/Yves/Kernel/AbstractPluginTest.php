<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\SprykerEngine\Yves\Kernel;

use SprykerEngine\Yves\Kernel\Locator;
use SprykerEngine\Yves\Kernel\PluginLocator;
use YvesUnit\SprykerEngine\Yves\Kernel\Fixtures\AbstractPlugin\Plugin\FooPlugin;

/**
 * @group SprykerEngine
 * @group Yves
 * @group Kernel
 * @group AbstractPlugin
 */
class AbstractPluginTest extends \PHPUnit_Framework_TestCase
{

    public function testGetFactoryMustReturnInstance()
    {
        $plugin = $this->locatePlugin();
        $factory = $plugin->getFactory();

        $this->assertInstanceOf('SprykerEngine\Yves\Kernel\Factory', $factory);
    }

    /**
     * @return FooPlugin
     */
    private function locatePlugin()
    {
        $locator = new PluginLocator(
            '\\YvesUnit\\SprykerEngine\\Yves\\{{bundle}}{{store}}\\Fixtures\\AbstractPlugin\\Factory'
        );

        $plugin = $locator->locate('Kernel', Locator::getInstance(), 'Foo');

        return $plugin;
    }
}
