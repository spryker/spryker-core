<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group GetAddressesTest
 * Add your own group annotations below this line
 */
class GetAddressesTest extends AbstractCustomerAddressFacadeTest
{
    /**
     * @return void
     */
    public function testGetAddressesHasCountry(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => 'testPassword',
        ]);
        $this->customerFacade->createAddressAndUpdateCustomerDefaultAddresses($customerTransfer->getShippingAddress()[0]);

        // Act
        $addressesTransfer = $this->customerFacade->getAddresses($customerTransfer);
        $addressTransfer = $addressesTransfer->getAddresses()[0];

        // Assert
        $this->assertSame(static::TESTER_FK_COUNTRY_GERMANY, $addressTransfer->getCountry()->getIdCountry());
    }
}
