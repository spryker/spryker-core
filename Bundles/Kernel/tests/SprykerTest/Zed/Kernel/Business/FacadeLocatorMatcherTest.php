<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Business\FacadeLocatorMatcher;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Business
 * @group Facade
 * @group FacadeLocatorMatcherTest
 * Add your own group annotations below this line
 */
class FacadeLocatorMatcherTest extends Unit
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
}
