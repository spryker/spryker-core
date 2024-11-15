<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OmsFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\Oms\OmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OmsFacade
 * @group AreOrderItemsSatisfiedByFlagTest
 * Add your own group annotations below this line
 */
class AreOrderItemsSatisfiedByFlagTest extends Unit
{
    /**
     * @var string
     */
    protected const ORDER_ITEM_STATUS_CANCELLED = 'cancelled';

    /**
     * @var string
     */
    protected const ORDER_ITEM_FLAGGED_EXCLUDE_FROM_CUSTOMER = 'exclude from customer';

    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected OmsBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testShouldReturnTrueWhenOrderReferenceIsProvidedAndAllOrderItemsSatisfiedByFlag(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems();
        $this->tester->setItemState($itemTransfer->offsetGet(0)->getIdSalesOrderItem(), static::ORDER_ITEM_STATUS_CANCELLED);
        $this->tester->setItemState($itemTransfer->offsetGet(1)->getIdSalesOrderItem(), static::ORDER_ITEM_STATUS_CANCELLED);

        // Act
        $result = $this->tester->getFacade()->areOrderItemsSatisfiedByFlag(
            (new OrderTransfer())->setOrderReference($orderTransfer->getOrderReferenceOrFail()),
            static::ORDER_ITEM_FLAGGED_EXCLUDE_FROM_CUSTOMER,
        );

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testShouldReturnTrueWhenIdSalesOrderIsProvidedAndAllOrderItemsSatisfiedByFlag(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems();
        $this->tester->setItemState($itemTransfer->offsetGet(0)->getIdSalesOrderItem(), static::ORDER_ITEM_STATUS_CANCELLED);
        $this->tester->setItemState($itemTransfer->offsetGet(1)->getIdSalesOrderItem(), static::ORDER_ITEM_STATUS_CANCELLED);

        // Act
        $result = $this->tester->getFacade()->areOrderItemsSatisfiedByFlag(
            (new OrderTransfer())->setIdSalesOrder($orderTransfer->getIdSalesOrderOrFail()),
            static::ORDER_ITEM_FLAGGED_EXCLUDE_FROM_CUSTOMER,
        );

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testShouldReturnFalseWhenAtLeastOneOrderItemIsNotSatisfiedByFlag(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(OmsBusinessTester::DEFAULT_OMS_PROCESS_NAME);
        $itemTransfer = $orderTransfer->getItems();
        $this->tester->setItemState($itemTransfer->offsetGet(0)->getIdSalesOrderItem(), static::ORDER_ITEM_STATUS_CANCELLED);

        // Act
        $result = $this->tester->getFacade()->areOrderItemsSatisfiedByFlag(
            $orderTransfer,
            static::ORDER_ITEM_FLAGGED_EXCLUDE_FROM_CUSTOMER,
        );

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenOrderReferenceAndIdSalesOrderAreNotProvided(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "idSalesOrder" of transfer `%s` is null.', OrderTransfer::class));

        // Act
        $result = $this->tester->getFacade()->areOrderItemsSatisfiedByFlag(
            new OrderTransfer(),
            static::ORDER_ITEM_FLAGGED_EXCLUDE_FROM_CUSTOMER,
        );
    }
}
