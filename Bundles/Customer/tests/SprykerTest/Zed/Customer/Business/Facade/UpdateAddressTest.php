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
 * @group UpdateAddressTest
 * Add your own group annotations below this line
 */
class UpdateAddressTest extends AbstractCustomerAddressFacadeTest
{
    /**
     * @return void
     */
    public function testUpdateAddress(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setFirstName(static::TESTER_NAME);
        $addressTransfer->setLastName(static::TESTER_NAME);
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);
        $this->assertNotNull($addressTransfer);

        $customerTransfer = $this->tester->getFacade()->getCustomer($customerTransfer);

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        $addressTransfer->setCity(static::TESTER_CITY);
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());

        // Act
        $addressTransfer = $this->customerFacade->updateAddress($addressTransfer);

        // Assert
        $this->assertNotNull($addressTransfer);
        $this->assertSame(static::TESTER_CITY, $addressTransfer->getCity());
    }
}
