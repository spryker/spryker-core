<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Spryker\Zed\Customer\Business\Exception\AddressNotFoundException;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPostDeletePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group DeleteCustomerTest
 * Add your own group annotations below this line
 */
class DeleteCustomerTest extends AbstractCustomerAddressFacadeTest
{
    /**
     * @return void
     */
    public function testDeleteCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();

        // Act
        $isSuccess = $this->tester->getFacade()->deleteCustomer($customerTransfer);

        // Assert
        $this->assertTrue($isSuccess);
    }

    /**
     * @return void
     */
    public function testExecutesCustomerPostDeletePlugins(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();

        $customerPostDeletePlugin = $this->getMockBuilder(CustomerPostDeletePluginInterface::class)->getMock();
        $this->tester->setDependency(CustomerDependencyProvider::PLUGINS_CUSTOMER_POST_DELETE, [$customerPostDeletePlugin]);

        // Assert
        $customerPostDeletePlugin
            ->expects($this->once())
            ->method('execute');

        // Act
        $this->tester->getFacade()->deleteCustomer($customerTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteCustomerWithDefaultAddresses(): void
    {
        // Arrange
        $customerTransfer = $this->createCustomerWithAddress();

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        $this->customerFacade->setDefaultBillingAddress($addressTransfer);
        $this->customerFacade->setDefaultShippingAddress($addressTransfer);

        // Assert
        $this->expectException(AddressNotFoundException::class);

        // Act
        $this->customerFacade->deleteCustomer($customerTransfer);
        $this->customerFacade->getAddress($addressTransfer);
    }
}
