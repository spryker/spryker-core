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
 * @group RenderAddressTest
 * Add your own group annotations below this line
 */
class RenderAddressTest extends AbstractCustomerAddressFacadeTest
{
    /**
     * @return void
     */
    public function testRenderAddress(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $addressTransfer = $this->createTestAddress($customerTransfer);
        $addressTransfer = $this->customerFacade->getAddress($addressTransfer);

        // Act
        $renderedAddress = $this->customerFacade->renderAddress($addressTransfer);

        // Assert
        $this->assertNotNull($renderedAddress);
    }
}
