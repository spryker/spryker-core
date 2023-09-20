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
 * @group FindCustomerAddressByIdTest
 * Add your own group annotations below this line
 */
class FindCustomerAddressByIdTest extends AbstractCustomerAddressFacadeTest
{
    /**
     * @return void
     */
    public function testCheckAddressExistsByIdCustomerAddressShouldReturnTrueOnExistedAddress(): void
    {
        // Arrange
        $customerTransfer = $this->createCustomerWithAddress();

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];
        $idCustomerAddress = $addressTransfer->getIdCustomerAddress();

        // Act
        $result = $this->customerFacade->findCustomerAddressById($idCustomerAddress);

        // Assert
        $this->assertNotNull($result);
    }

    /**
     * @return void
     */
    public function testCheckAddressExistsByIdCustomerAddressShouldReturnFalseOnNonExistedAddress(): void
    {
        // Arrange
        $customerTransfer = $this->createCustomerWithAddress();

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];
        $idCustomerAddress = $addressTransfer->getIdCustomerAddress();
        $this->customerFacade->deleteAddress($addressTransfer);

        // Act
        $result = $this->customerFacade->findCustomerAddressById($idCustomerAddress);

        // Assert
        $this->assertNull($result);
    }
}
