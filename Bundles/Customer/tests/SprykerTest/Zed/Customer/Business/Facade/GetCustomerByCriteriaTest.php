<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\CustomerCriteriaTransfer;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group GetCustomerByCriteriaTest
 * Add your own group annotations below this line
 */
class GetCustomerByCriteriaTest extends AbstractCustomerFacadeTest
{
    /**
     * @return void
     */
    public function testGetCustomerByCriteriaShouldFindExistingCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $customerCriteriaTransfer = (new CustomerCriteriaTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference());

        $customerTransferExpanderPlugin = $this
            ->getMockBuilder(CustomerTransferExpanderPluginInterface::class)
            ->getMock();
        $customerTransferExpanderPlugin->expects($this->never())->method('expandTransfer');
        $this->tester->setDependency(
            CustomerDependencyProvider::PLUGINS_CUSTOMER_TRANSFER_EXPANDER,
            [$customerTransferExpanderPlugin],
        );

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()
            ->getCustomerByCriteria($customerCriteriaTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess(), 'Customer must be findable by customer reference');
    }

    /**
     * @return void
     */
    public function testGetCustomerByCriteriaShouldFailToFindNonExistingCustomer(): void
    {
        // Arrange
        $customerCriteriaTransfer = (new CustomerCriteriaTransfer())
            ->setCustomerReference('DE--NO-PRESENT');

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()
            ->getCustomerByCriteria($customerCriteriaTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess(), 'Non-existing customer must be not findable.');
    }

    /**
     * @return void
     */
    public function testGetCustomerByCriteriaShouldRunExpanders(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $customerCriteriaTransfer = (new CustomerCriteriaTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setWithExpanders(true);

        $customerTransferExpanderPlugin = $this
            ->getMockBuilder(CustomerTransferExpanderPluginInterface::class)
            ->getMock();
        $customerTransferExpanderPlugin->expects($this->once())->method('expandTransfer');
        $this->tester->setDependency(
            CustomerDependencyProvider::PLUGINS_CUSTOMER_TRANSFER_EXPANDER,
            [$customerTransferExpanderPlugin],
        );

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()
            ->getCustomerByCriteria($customerCriteriaTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess(), 'Customer must be findable by customer reference');
    }
}
