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
 * @group GetCustomerTest
 * Add your own group annotations below this line
 */
class GetCustomerTest extends AbstractCustomerFacadeTest
{
    /**
     * @return void
     */
    public function testGetCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getCustomerFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());

        // Act
        $customerTransfer = $this->tester->getCustomerFacade()->getCustomer($customerTransfer);

        // Assert
        $this->assertNotNull($customerTransfer->getIdCustomer());
    }
}
