<?php

namespace Unit\SprykerEngine\Client\Kernel;

use SprykerEngine\Client\Kernel\ClientLocatorMatcher;

/**
 * @group Kernel
 * @group Business
 * @group Locator
 */
class ClientLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testMatchShouldReturnTrueIfMethodStartsWithFacade()
    {
        $this->assertTrue((new ClientLocatorMatcher())->match('client'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithFacade()
    {
        $this->assertFalse((new ClientLocatorMatcher())->match('locatorFoo'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithFacadeButFacadeInString()
    {
        $this->assertFalse((new ClientLocatorMatcher())->match('locatorClient'));
    }

    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new ClientLocatorMatcher())->filter('clientFoo'));
    }

}
