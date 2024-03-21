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
 * @group FindCustomerByReferenceTest
 * Add your own group annotations below this line
 */
class FindCustomerByReferenceTest extends AbstractCustomerFacadeTest
{
    /**
     * @return void
     */
    public function testFindCustomerByReference(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer(['password' => static::VALUE_VALID_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->findCustomerByReference($customerTransfer->getCustomerReference());

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertSame($customerTransfer->getCustomerReference(), $customerResponseTransfer->getCustomerTransfer()->getCustomerReference());
    }
}
