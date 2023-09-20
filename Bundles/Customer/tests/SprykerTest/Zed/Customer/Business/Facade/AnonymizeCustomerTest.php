<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group AnonymizeCustomerTest
 * Add your own group annotations below this line
 */
class AnonymizeCustomerTest extends AbstractCustomerFacadeTest
{
    /**
     * @return void
     */
    public function testAnonymizeCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer(['password' => static::VALUE_VALID_PASSWORD]);

        // Act
        $this->tester->getFacade()->anonymizeCustomer($customerTransfer);

        // Assert
        $this->expectException(CustomerNotFoundException::class);
        $this->tester->getFacade()->getCustomer($customerTransfer);
    }
}
