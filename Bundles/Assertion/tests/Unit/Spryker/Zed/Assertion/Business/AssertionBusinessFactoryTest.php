<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Assertion\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Assertion\Business\AssertionBusinessFactory;
use Spryker\Zed\Assertion\Business\Model\Assertion;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Assertion
 * @group Business
 * @group AssertionBusinessFactoryTest
 */
class AssertionBusinessFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCreateAssertion()
    {
        $factory = new AssertionBusinessFactory();
        $this->assertInstanceOf(Assertion::class, $factory->createAssertion());
    }

}
