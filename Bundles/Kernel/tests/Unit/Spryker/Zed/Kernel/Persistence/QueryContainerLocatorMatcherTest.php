<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\Persistence;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Kernel\Persistence\QueryContainerLocatorMatcher;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group Persistence
 * @group QueryContainerLocatorMatcherTest
 */
class QueryContainerLocatorMatcherTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testMatchShouldReturnTrueIfMethodStartsWithQueryContainer()
    {
        $this->assertTrue((new QueryContainerLocatorMatcher())->match('queryContainer'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithQueryContainer()
    {
        $this->assertFalse((new QueryContainerLocatorMatcher())->match('locatorFoo'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithQueryContainerButQueryContainerInString()
    {
        $this->assertFalse((new QueryContainerLocatorMatcher())->match('locatorQueryContainer'));
    }

}
