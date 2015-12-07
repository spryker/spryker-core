<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\Business\FacadeLocatorMatcher;

/**
 * @group Kernel
 * @group Business
 * @group Locator
 * @group FacadeLocator
 * @group FacadeLocatorMatcher
 */
class FacadeLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testMatchShouldReturnTrueIfMethodStartsWithFacade()
    {
        $this->assertTrue((new FacadeLocatorMatcher())->match('facadeFoo'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithFacade()
    {
        $this->assertFalse((new FacadeLocatorMatcher())->match('locatorFoo'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithFacadeButFacadeInString()
    {
        $this->assertFalse((new FacadeLocatorMatcher())->match('locatorFacade'));
    }

    /**
     * @return void
     */
    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new FacadeLocatorMatcher())->filter('facadeFoo'));
    }

}
