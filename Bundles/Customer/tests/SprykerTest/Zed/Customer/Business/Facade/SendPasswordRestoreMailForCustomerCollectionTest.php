<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\CustomerCollectionTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group SendPasswordRestoreMailForCustomerCollectionTest
 * Add your own group annotations below this line
 */
class SendPasswordRestoreMailForCustomerCollectionTest extends AbstractCustomerFacadeTest
{
    /**
     * @return void
     */
    public function testSendPasswordRestoreMailForCustomerCollectionShouldSetRestorePasswordKey(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            'password' => static::VALUE_VALID_PASSWORD,
        ]);

        //Act
        $this->tester->getCustomerFacade()->sendPasswordRestoreMailForCustomerCollection(
            (new CustomerCollectionTransfer())->addCustomer($customerTransfer),
        );

        $customerResponseTransfer = $this->tester->getCustomerFacade()->findCustomerByReference($customerTransfer->getCustomerReference());
        // Assert
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer()->getRestorePasswordKey());
    }
}
