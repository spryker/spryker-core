<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel;

use Codeception\Test\Unit;
use Spryker\Service\Kernel\ServiceLocatorMatcher;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Kernel
 * @group ServiceLocatorMatcherTest
 * Add your own group annotations below this line
 */
class ServiceLocatorMatcherTest extends Unit
{
    /**
     * @return void
     */
    public function testMatchShouldReturnTrueIfMethodStartsWithService()
    {
        $this->assertTrue((new ServiceLocatorMatcher())->match('service'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithService()
    {
        $this->assertFalse((new ServiceLocatorMatcher())->match('locatorFoo'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithServiceButServiceInString()
    {
        $this->assertFalse((new ServiceLocatorMatcher())->match('locatorService'));
    }
}
