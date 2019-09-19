<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Assertion\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Assertion\Business\AssertionBusinessFactory;
use Spryker\Zed\Assertion\Business\Model\Assertion;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Assertion
 * @group Business
 * @group AssertionBusinessFactoryTest
 * Add your own group annotations below this line
 */
class AssertionBusinessFactoryTest extends Unit
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
