<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\Communication\PluginLocatorMatcher;

/**
 * @group Kernel
 * @group Business
 * @group Locator
 * @group PluginLocator
 * @group PluginLocatorMatcher
 */
class PluginLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testMatchShouldReturnTrueIfMethodStartsWithPlugin()
    {
        $this->assertTrue((new PluginLocatorMatcher())->match('plugin'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithPlugin()
    {
        $this->assertFalse((new PluginLocatorMatcher())->match('locatorFoo'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithPluginButPluginInString()
    {
        $this->assertFalse((new PluginLocatorMatcher())->match('locatorPlugin'));
    }

    /**
     * @return void
     */
    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new PluginLocatorMatcher())->filter('pluginFoo'));
    }

}
