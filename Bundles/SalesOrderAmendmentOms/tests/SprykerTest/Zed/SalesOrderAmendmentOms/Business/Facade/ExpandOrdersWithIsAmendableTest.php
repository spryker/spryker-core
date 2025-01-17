<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Business
 * @group Facade
 * @group ExpandOrdersWithIsAmendableTest
 * Add your own group annotations below this line
 */
class ExpandOrdersWithIsAmendableTest extends Unit
{
    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_PAYMENT_PENDING = 'payment pending';

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_PAID = 'paid';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsBusinessTester
     */
    protected SalesOrderAmendmentOmsBusinessTester $tester;

    /**
     * @return void
     */
    public function testThrowsExceptionWhenOrderReferenceIsNotProvided(): void
    {
        //Arrange
        $orderTransfer = new OrderTransfer();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "orderReference" of transfer `Generated\Shared\Transfer\OrderTransfer` is null.');

        //Act
        $this->tester->getFacade()->expandOrdersWithIsAmendable([$orderTransfer]);
    }

    /**
     * @dataProvider expandsOrdersDataProvider
     *
     * @param string $firstOrderItem1StateName
     * @param string $firstOrderItem2StateName
     * @param bool $firstOrderExpectedIsAmendable
     *
     * @return void
     */
    public function testExpandsOrdersWithCorrectIsAmendableFlag(
        string $firstOrderItem1StateName,
        string $firstOrderItem2StateName,
        bool $firstOrderExpectedIsAmendable
    ): void {
        // Arrange
        $this->tester->configureOrderAmendmentTestStateMachine();
        $saveOrderTransfer1 = $this->tester->haveOrderWithTwoItems();
        $saveOrderTransfer2 = $this->tester->haveOrderWithTwoItems();
        $orderTransfer1 = (new OrderTransfer())->setOrderReference($saveOrderTransfer1->getOrderReferenceOrFail())->setItems($saveOrderTransfer1->getOrderItems());
        $orderTransfer2 = (new OrderTransfer())->setOrderReference($saveOrderTransfer2->getOrderReferenceOrFail())->setItems($saveOrderTransfer2->getOrderItems());
        $this->tester->setItemState($orderTransfer1->getItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), $firstOrderItem1StateName);
        $this->tester->setItemState($orderTransfer1->getItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), $firstOrderItem2StateName);
        $this->tester->setItemState($orderTransfer2->getItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAYMENT_PENDING);
        $this->tester->setItemState($orderTransfer2->getItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAYMENT_PENDING);

        // Act
        $expandedOrders = $this->tester->getFacade()->expandOrdersWithIsAmendable([$orderTransfer1, $orderTransfer2]);

        // Assert
        $this->assertSame($firstOrderExpectedIsAmendable, $expandedOrders[0]->getIsAmendable());
        $this->assertTrue($expandedOrders[1]->getIsAmendable());
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function expandsOrdersDataProvider(): array
    {
        return [
            'Both items are amendable' => [static::ORDER_ITEM_STATE_PAYMENT_PENDING, static::ORDER_ITEM_STATE_PAYMENT_PENDING, true],
            'One item is not amendable' => [static::ORDER_ITEM_STATE_PAYMENT_PENDING, static::ORDER_ITEM_STATE_PAID, false],
            'Both items are not amendable' => [static::ORDER_ITEM_STATE_PAID, static::ORDER_ITEM_STATE_PAID, false],
        ];
    }
}
