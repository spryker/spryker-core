<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\Communication\ConsoleLocatorMatcher;

/**
 * @group Kernel
 * @group Business
 * @group Locator
 * @group ConsoleLocator
 * @group ConsoleLocatorMatcher
 */
class ConsoleLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testMatchShouldReturnTrueIfMethodStartsWithConsole()
    {
        $this->assertTrue((new ConsoleLocatorMatcher())->match('console'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithConsole()
    {
        $this->assertFalse((new ConsoleLocatorMatcher())->match('locatorFoo'));
    }

    public function testMatchShouldReturnFalseIfMethodNotStartsWithConsoleButConsoleInString()
    {
        $this->assertFalse((new ConsoleLocatorMatcher())->match('locatorConsole'));
    }

    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new ConsoleLocatorMatcher())->filter('consoleFoo'));
    }

}
