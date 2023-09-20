<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group ConfirmRegistrationTest
 * Add your own group annotations below this line
 */
class ConfirmRegistrationTest extends AbstractCustomerFacadeTest
{
    /**
     * @return void
     */
    public function testConfirmRegistration(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());

        // Assert
        $this->assertNotNull($customerTransfer->getRegistered());
    }
}
