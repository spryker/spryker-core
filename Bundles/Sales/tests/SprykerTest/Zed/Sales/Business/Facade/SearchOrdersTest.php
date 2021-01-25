<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\OrderListFormatTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group SearchOrdersTest
 * Add your own group annotations below this line
 */
class SearchOrdersTest extends Test
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_LIKE_ORDER_REFERENCE = 'fake-like-order-reference';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
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
    public function testSearchOrdersCheckLikeConditionWithLowerCase(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $orderListTransfer = (new OrderListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setFormat((new OrderListFormatTransfer())->setExpandWithItems(false))
            ->setPagination((new PaginationTransfer())->setPage(1)->setMaxPerPage(10))
            ->addFilterField((new FilterFieldTransfer())->setType('all')->setValue(mb_strtolower($orderTransfer->getOrderReference())));

        // Act
        $storedOrderListTransfer = $this->tester
            ->getFacade()
            ->searchOrders($orderListTransfer);

        // Assert
        $this->assertCount(1, $storedOrderListTransfer->getOrders());
    }

    /**
     * @return void
     */
    public function testSearchOrdersCheckLikeConditionWithFirstLetterInUpperCase(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $orderListTransfer = (new OrderListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setFormat((new OrderListFormatTransfer())->setExpandWithItems(false))
            ->setPagination((new PaginationTransfer())->setPage(1)->setMaxPerPage(10))
            ->addFilterField((new FilterFieldTransfer())->setType('all')->setValue(ucfirst(mb_strtolower($orderTransfer->getOrderReference()))));

        // Act
        $storedOrderListTransfer = $this->tester
            ->getFacade()
            ->searchOrders($orderListTransfer);

        // Assert
        $this->assertCount(1, $storedOrderListTransfer->getOrders());
    }

    /**
     * @return void
     */
    public function testSearchOrdersCheckLikeConditionWithFakeOrderReference(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $orderListTransfer = (new OrderListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setFormat((new OrderListFormatTransfer())->setExpandWithItems(false))
            ->setPagination((new PaginationTransfer())->setPage(1)->setMaxPerPage(10))
            ->addFilterField((new FilterFieldTransfer())->setType('all')->setValue(static::FAKE_LIKE_ORDER_REFERENCE));

        // Act
        $storedOrderListTransfer = $this->tester
            ->getFacade()
            ->searchOrders($orderListTransfer);

        // Assert
        $this->assertCount(0, $storedOrderListTransfer->getOrders());
    }
}
