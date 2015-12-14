<?php

namespace Unit\SprykerEngine\Client\Kernel;

use SprykerEngine\Client\Kernel\ClientLocatorMatcher;

/**
 * @group SprykerEngine
 * @group Client
 * @group Kernel
 * @group ClientLocatorMatcher
 */
class ClientLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testMatchShouldReturnTrueIfMethodStartsWithClient()
    {
        $this->assertTrue((new ClientLocatorMatcher())->match('client'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithClient()
    {
        $this->assertFalse((new ClientLocatorMatcher())->match('locatorFoo'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithClientButClientInString()
    {
        $this->assertFalse((new ClientLocatorMatcher())->match('locatorClient'));
    }

    /**
     * @return void
     */
    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new ClientLocatorMatcher())->filter('clientFoo'));
    }

}
