<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Service\Kernel;

use Spryker\Service\Kernel\ServiceLocatorMatcher;

/**
 * @group Unit
 * @group Spryker
 * @group Service
 * @group Kernel
 * @group ServiceLocatorMatcherTest
 */
class ServiceLocatorMatcherTest extends \PHPUnit_Framework_TestCase
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
