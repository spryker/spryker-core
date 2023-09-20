<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Spryker\Zed\Customer\Business\Exception\AddressNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group GetDefaultBillingAddressTest
 * Add your own group annotations below this line
 */
class GetDefaultBillingAddressTest extends AbstractCustomerAddressFacadeTest
{
    /**
     * @return void
     */
    public function testGetDefaultBillingAddress(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $this->createTestAddress($customerTransfer);
        // Act
        $addressTransfer = $this->customerFacade->getDefaultBillingAddress($customerTransfer);

        // Assert
        $this->assertNotNull($addressTransfer);
    }

    /**
     * @return void
     */
    public function testGetDefaultBillingAddressReturnsFirstAddressOfCustomerWhenNoDefaultAddress(): void
    {
        // Arrange
        $customerTransfer = $this->createCustomerWithAddress();

        // Act
        $addressTransfer = $this->customerFacade->getDefaultBillingAddress($customerTransfer);

        // Assert
        $this->assertSame($customerTransfer->getIdCustomer(), $addressTransfer->getFkCustomer());
    }

    /**
     * @return void
     */
    public function testGetDefaultBillingAddressTrowsAddressNotFoundExceptionWhenCustomerDontHaveAddress(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();

        // Assert
        $this->expectException(AddressNotFoundException::class);
        $this->expectExceptionMessage("Address not found for ID `` (and optional customer ID `{$customerTransfer->getIdCustomer()}`).");

        // Act
        $this->customerFacade->getDefaultBillingAddress($customerTransfer);
    }
}
