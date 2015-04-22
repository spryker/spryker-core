<?php

namespace Unit\SprykerEngine\Zed\Kernel\Persistence\Propel;

use SprykerEngine\Zed\Kernel\Persistence\Propel\SpyityLocatorMatcher;

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
        $this->assertTrue((new SpyityLocatorMatcher())->match('entityFoo'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithEntity()
    {
        $this->assertFalse((new SpyityLocatorMatcher())->match('locatorFoo'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithEntityButEntityInString()
    {
        $this->assertFalse((new SpyityLocatorMatcher())->match('locatorEntity'));
    }

    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new SpyityLocatorMatcher())->filter('entityFoo'));
    }
}
