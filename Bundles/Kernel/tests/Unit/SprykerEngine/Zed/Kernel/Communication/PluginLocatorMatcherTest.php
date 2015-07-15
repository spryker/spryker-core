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

    public function testMatchShouldReturnTrueIfMethodStartsWithPlugin()
    {
        $this->assertTrue((new PluginLocatorMatcher())->match('plugin'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithPlugin()
    {
        $this->assertFalse((new PluginLocatorMatcher())->match('locatorFoo'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithPluginButPluginInString()
    {
        $this->assertFalse((new PluginLocatorMatcher())->match('locatorPlugin'));
    }

    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new PluginLocatorMatcher())->filter('pluginFoo'));
    }

}
