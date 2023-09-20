<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\AddressTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group CreateAddressTest
 * Add your own group annotations below this line
 */
class CreateAddressTest extends AbstractCustomerAddressFacadeTest
{
    /**
     * @return void
     */
    public function testNewAddress(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setFirstName(static::TESTER_NAME);
        $addressTransfer->setLastName(static::TESTER_NAME);

        // Act
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);

        // Assert
        $this->assertNotNull($addressTransfer);
    }
}
