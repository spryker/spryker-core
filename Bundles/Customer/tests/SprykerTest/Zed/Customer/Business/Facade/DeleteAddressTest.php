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
 * @group DeleteAddressTest
 * Add your own group annotations below this line
 */
class DeleteAddressTest extends AbstractCustomerAddressFacadeTest
{
    /**
     * @return void
     */
    public function testDeleteAddress(): void
    {
        // Arrange
        $customerTransfer = $this->createCustomerWithAddress();

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        // Act
        $deletedAddress = $this->customerFacade->deleteAddress($addressTransfer);

        // Assert
        $this->assertNotNull($deletedAddress);
    }

    /**
     * @return void
     */
    public function testDeleteDefaultAddress(): void
    {
        // Arrange
        $customerTransfer = $this->createCustomerWithAddress();

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        $this->customerFacade->setDefaultBillingAddress($addressTransfer);

        // Act
        $this->customerFacade->deleteAddress($addressTransfer);
        $customerTransfer = $this->tester->getFacade()->getCustomer($customerTransfer);

        // Assert
        $this->assertNull($customerTransfer->getDefaultBillingAddress());
    }
}
