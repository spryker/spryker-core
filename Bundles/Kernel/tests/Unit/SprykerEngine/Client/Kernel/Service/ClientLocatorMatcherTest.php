<?php

namespace Unit\SprykerEngine\Client\Kernel\Service;

use SprykerEngine\Client\Kernel\Service\ClientLocatorMatcher;

/**
 * @group SprykerEngine
 * @group Client
 * @group Kernel
 * @group ClientLocatorMatcher
 */
class ClientLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testMatchShouldReturnTrueIfMethodStartsWithClient()
    {
        $this->assertTrue((new ClientLocatorMatcher())->match('client'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithClient()
    {
        $this->assertFalse((new ClientLocatorMatcher())->match('locatorFoo'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithClientButClientInString()
    {
        $this->assertFalse((new ClientLocatorMatcher())->match('locatorClient'));
    }

    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new ClientLocatorMatcher())->filter('clientFoo'));
    }

}
