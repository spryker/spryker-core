<?php

namespace Unit\SprykerEngine\Zed\Kernel\Persistence\Propel;

use SprykerEngine\Zed\Kernel\Persistence\Propel\EntityLocatorMatcher;

/**
 * @group Kernel
 * @group Persistence
 * @group Locator
 * @group EntityLocator
 * @group EntityLocatorMatcher
 */
class EntityLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testMatchShouldReturnTrueIfMethodStartsWithFacade()
    {
        $this->assertTrue((new EntityLocatorMatcher())->match('entityFoo'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithEntity()
    {
        $this->assertFalse((new EntityLocatorMatcher())->match('locatorFoo'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithEntityButEntityInString()
    {
        $this->assertFalse((new EntityLocatorMatcher())->match('locatorEntity'));
    }

    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new EntityLocatorMatcher())->filter('entityFoo'));
    }
}
