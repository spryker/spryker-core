<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Business
 * @group SalesReturnFacade
 * @group GetReturnableItemsTest
 * Add your own group annotations below this line
 */
class GetReturnableItemsTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    protected const SHIPPED_STATE_NAME = 'shipped';
    protected const DELIVERED_STATE_NAME = 'delivered';

    protected const FAKE_STATE_NAME = 'FAKE_STATE_NAME';
    protected const FAKE_CUSTOMER_REFERENCE = 'FAKE_CUSTOMER_REFERENCE';
    protected const FAKE_ORDER_REFERENCE = 'FAKE_ORDER_REFERENCE';

    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testGetReturnableItemsRetrievesOrderItemsByOrderReferencesFromOneCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $firstOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $secondOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $firstItemTransfer = $firstOrderTransfer->getItems()->getIterator()->current();
        $secondItemTransfer = $secondOrderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($firstItemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);
        $this->tester->setItemState($secondItemTransfer->getIdSalesOrderItem(), static::DELIVERED_STATE_NAME);

        $returnableItemFilterTransfer = (new ReturnableItemFilterTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->addOrderReference($firstOrderTransfer->getOrderReference())
            ->addOrderReference($secondOrderTransfer->getOrderReference());

        // Act
        $itemCollectionTransfer = $this->tester
            ->getFacade()
            ->getReturnableItems($returnableItemFilterTransfer);

        // Assert
        $this->assertCount(2, $itemCollectionTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testGetReturnableItemsRetrievesOrderItemsInNonReturnableStates(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::FAKE_STATE_NAME);

        $returnableItemFilterTransfer = (new ReturnableItemFilterTransfer())
            ->setCustomerReference($orderTransfer->getCustomer()->getCustomerReference())
            ->addOrderReference($orderTransfer->getOrderReference());

        // Act
        $itemCollectionTransfer = $this->tester
            ->getFacade()
            ->getReturnableItems($returnableItemFilterTransfer);

        // Assert
        $this->assertEmpty($itemCollectionTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testGetReturnableItemsRetrievesOrderItemsWithFakeCustomerReference(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $returnableItemFilterTransfer = (new ReturnableItemFilterTransfer())
            ->setCustomerReference(static::FAKE_CUSTOMER_REFERENCE)
            ->addOrderReference($orderTransfer->getOrderReference());

        // Act
        $itemCollectionTransfer = $this->tester
            ->getFacade()
            ->getReturnableItems($returnableItemFilterTransfer);

        // Assert
        $this->assertEmpty($itemCollectionTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testGetReturnableItemsRetrievesOrderItemsWithFakeOrderReference(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);

        $returnableItemFilterTransfer = (new ReturnableItemFilterTransfer())
            ->setCustomerReference($orderTransfer->getCustomer()->getCustomerReference())
            ->addOrderReference(static::FAKE_ORDER_REFERENCE);

        // Act
        $itemCollectionTransfer = $this->tester
            ->getFacade()
            ->getReturnableItems($returnableItemFilterTransfer);

        // Assert
        $this->assertEmpty($itemCollectionTransfer->getItems());
    }
}
