<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Kernel\Business\FacadeLocatorMatcher;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group Business
 * @group FacadeLocatorMatcherTest
 */
class FacadeLocatorMatcherTest extends PHPUnit_Framework_TestCase
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
