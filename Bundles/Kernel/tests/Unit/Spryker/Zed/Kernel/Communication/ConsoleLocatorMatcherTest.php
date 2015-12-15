<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Business;

use Spryker\Zed\Kernel\Communication\ConsoleLocatorMatcher;

/**
 * @group Kernel
 * @group Business
 * @group Locator
 * @group ConsoleLocator
 * @group ConsoleLocatorMatcher
 */
class ConsoleLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testMatchShouldReturnTrueIfMethodStartsWithConsole()
    {
        $this->assertTrue((new ConsoleLocatorMatcher())->match('console'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithConsole()
    {
        $this->assertFalse((new ConsoleLocatorMatcher())->match('locatorFoo'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithConsoleButConsoleInString()
    {
        $this->assertFalse((new ConsoleLocatorMatcher())->match('locatorConsole'));
    }

    /**
     * @return void
     */
    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new ConsoleLocatorMatcher())->filter('consoleFoo'));
    }

}
