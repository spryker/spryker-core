<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerTest\Zed\Customer\CustomerBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group CreateAddressWithDuplicateCheckTest
 * Add your own group annotations below this line
 */
class CreateAddressWithDuplicateCheckTest extends AbstractCustomerAddressFacadeTest
{
    protected CustomerBusinessTester $tester;

    protected string $password = 'catface';

    /**
     * @return void
     */
    public function testCreateAddressFromExternalServiceWithExistingAddressDataShouldReuseExistingAddressWhenTheAddressAlreadyExists(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([CustomerTransfer::PASSWORD => $this->password]);
        $addressTransfer1 = $this->tester->haveCustomerAddress([AddressTransfer::EMAIL => $customerTransfer->getEmail()]);

        // Act
        $addressTransfer2 = clone $addressTransfer1;
        $addressTransfer2->setIsFromExternalService(true);

        $addressTransfer2 = $this->customerFacade->createAddress($addressTransfer2);

        // Assert
        $this->assertEquals($addressTransfer1->getIdCustomerAddress(), $addressTransfer2->getIdCustomerAddress());
    }

    /**
     * @return void
     */
    public function testCreateAddressWithDifferentAddressDataShouldCreateNewAddress(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([CustomerTransfer::PASSWORD => $this->password]);
        $addressTransfer1 = $this->tester->haveCustomerAddress([AddressTransfer::EMAIL => $customerTransfer->getEmail()]);

        // Create second address with different data
        $addressTransfer2 = clone $addressTransfer1;
        $addressTransfer2->setAddress1('Different Street 42');

        // Act
        $addressTransfer2 = $this->customerFacade->createAddress($addressTransfer2);

        // Assert
        $this->assertNotEquals($addressTransfer1->getIdCustomerAddress(), $addressTransfer2->getIdCustomerAddress());
    }
}
