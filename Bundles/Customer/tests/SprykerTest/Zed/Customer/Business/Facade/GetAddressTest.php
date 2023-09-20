<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Customer\Business\Exception\AddressNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group GetAddressTest
 * Add your own group annotations below this line
 */
class GetAddressTest extends AbstractCustomerAddressFacadeTest
{
    /**
     * @return void
     */
    public function testGetAddressReturnsAddressByProvidedAddressIdWhenCustomerIdNotSpecified(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $addressTransfer = $this->createTestAddress($customerTransfer);
        $addressTransfer->setCustomerId(null);

        // Act
        $addressTransfer = $this->customerFacade->getAddress($addressTransfer);

        // Assert
        $this->assertSame($customerTransfer->getIdCustomer(), $addressTransfer->getFkCustomer());
    }

    /**
     * @return void
     */
    public function testGetAddressReturnsFirstAddressOfCustomerWhenNoDefaultAddress(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $addressTransfer = $this->createTestAddress($customerTransfer);

        // Act
        $addressTransfer = $this->customerFacade->getAddress($addressTransfer);

        // Assert
        $this->assertSame($customerTransfer->getIdCustomer(), $addressTransfer->getFkCustomer());
    }

    /**
     * @return void
     */
    public function testGetAddressTrowsAddressNotFoundExceptionWhenAddressIdAndCustomerIdNotPassed(): void
    {
        // Arrange
        $addressTransfer = new AddressTransfer();

        // Assert
        $this->expectException(AddressNotFoundException::class);
        $this->expectExceptionMessage('Address not found for ID `` (and optional customer ID ``).');

        // Act
        $this->customerFacade->getAddress($addressTransfer);
    }
}
