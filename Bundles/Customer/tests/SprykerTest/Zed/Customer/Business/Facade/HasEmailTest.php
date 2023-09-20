<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use SprykerTest\Zed\Customer\CustomerBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group HasEmailTest
 * Add your own group annotations below this line
 */
class HasEmailTest extends AbstractCustomerFacadeTest
{
    /**
     * @return void
     */
    public function testHasEmailReturnsFalseWithoutCustomer(): void
    {
        // Act
        $hasEmail = $this->tester->getFacade()->hasEmail(CustomerBusinessTester::TESTER_EMAIL);

        // Assert
        $this->assertFalse($hasEmail);
    }

    /**
     * @return void
     */
    public function testHasEmailReturnsTrueWithCustomer(): void
    {
        // Arrange
        $this->tester->createTestCustomer();

        // Act
        $hasEmail = $this->tester->getFacade()->hasEmail(CustomerBusinessTester::TESTER_EMAIL);

        // Assert
        $this->assertTrue($hasEmail);
    }

    /**
     * @return void
     */
    public function testHasEmail(): void
    {
        // Arrange
        $hasEmail = $this->getFacade()->hasEmail('foo@bar.com');

        // Assert
        $this->assertTrue($hasEmail);
    }
}
