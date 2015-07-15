<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\Persistence\QueryContainerLocatorMatcher;

/**
 * @group Kernel
 * @group Business
 * @group Locator
 * @group QueryContainerLocator
 * @group QueryContainerLocatorMatcher
 */
class QueryContainerLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testMatchShouldReturnTrueIfMethodStartsWithQueryContainer()
    {
        $this->assertTrue((new QueryContainerLocatorMatcher())->match('queryContainer'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithQueryContainer()
    {
        $this->assertFalse((new QueryContainerLocatorMatcher())->match('locatorFoo'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithQueryContainerButQueryContainerInString()
    {
        $this->assertFalse((new QueryContainerLocatorMatcher())->match('locatorQueryContainer'));
    }

    public function testFilterShouldReturnEmptyString()
    {
        $this->assertSame('', (new QueryContainerLocatorMatcher())->filter('queryContainer'));
    }

}
