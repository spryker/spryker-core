<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClientLocatorMatcher;

/**
 * @group Spryker
 * @group Client
 * @group Kernel
 * @group ClientLocatorMatcher
 */
class ClientLocatorMatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testMatchShouldReturnTrueIfMethodStartsWithClient()
    {
        $this->assertTrue((new ClientLocatorMatcher())->match('client'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithClient()
    {
        $this->assertFalse((new ClientLocatorMatcher())->match('locatorFoo'));
    }

    /**
     * @return void
     */
    public function testMatchShouldReturnFalseIfMethodNotStartsWithClientButClientInString()
    {
        $this->assertFalse((new ClientLocatorMatcher())->match('locatorClient'));
    }

    /**
     * @return void
     */
    public function testFilterShouldReturnClassName()
    {
        $this->assertSame('Foo', (new ClientLocatorMatcher())->filter('clientFoo'));
    }

}
