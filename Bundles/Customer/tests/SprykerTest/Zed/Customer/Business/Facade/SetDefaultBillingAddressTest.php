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
 * @group SetDefaultBillingAddressTest
 * Add your own group annotations below this line
 */
class SetDefaultBillingAddressTest extends AbstractCustomerAddressFacadeTest
{
    /**
     * @return void
     */
    public function testSetDefaultBillingAddress(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setFirstName(static::TESTER_NAME);
        $addressTransfer->setLastName(static::TESTER_NAME);
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);
        $this->assertNotNull($addressTransfer);
        $customerTransfer = $this->tester->getCustomerFacade()->getCustomer($customerTransfer);

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        // Act
        $isSuccess = $this->customerFacade->setDefaultBillingAddress($addressTransfer);

        // Assert
        $this->assertTrue($isSuccess);
    }
}
