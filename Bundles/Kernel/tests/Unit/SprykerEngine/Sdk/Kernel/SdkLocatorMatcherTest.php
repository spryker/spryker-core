<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Sdk\Kernel;

use SprykerEngine\Sdk\Kernel\SdkLocatorMatcher;

/**
 * @group Kernel
 * @group Business
 * @group Locator
 */
class SdkLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testMatchShouldReturnTrueIfMethodStartsWithFacade()
    {
        $this->assertTrue((new SdkLocatorMatcher())->match('sdk'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithFacade()
    {
        $this->assertFalse((new SdkLocatorMatcher())->match('locatorFoo'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithFacadeButFacadeInString()
    {
        $this->assertFalse((new SdkLocatorMatcher())->match('locatorSdk'));
    }

    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new SdkLocatorMatcher())->filter('sdkFoo'));
    }

}
