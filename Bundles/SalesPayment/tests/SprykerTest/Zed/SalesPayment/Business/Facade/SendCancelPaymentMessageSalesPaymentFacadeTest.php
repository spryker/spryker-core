<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPayment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CancelPaymentTransfer;
use Generated\Shared\Transfer\EventPaymentTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\SalesPayment\Business\Exception\OrderNotFoundException;
use Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToSalesFacadeInterface;
use Spryker\Zed\SalesPayment\SalesPaymentDependencyProvider;
use SprykerTest\Zed\SalesPayment\SalesPaymentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesPayment
 * @group Business
 * @group Facade
 * @group Facade
 * @group SendCancelPaymentMessageSalesPaymentFacadeTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SalesPayment\SalesPaymentBusinessTester $tester
 */
class SendCancelPaymentMessageSalesPaymentFacadeTest extends Unit
{
    /**
     * @var \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected $salesPaymentFacade;

    /**
     * @dataProvider orderItemsDataProviderForCancelSuccessProcess
     *
     * @param array $orderData
     * @param array $sentItemIds
     *
     * @return void
     */
    public function testSendCancelPaymentMessageSuccess(array $orderData, array $sentItemIds): void
    {
        // Arrange
        $this->mockSalesFacade($orderData);

        $eventPaymentTransfer = (new EventPaymentTransfer())
            ->setIdSalesOrder($orderData[OrderTransfer::ID_SALES_ORDER])
            ->setOrderItemIds($sentItemIds);

        // Act
        $this->tester->getFacade()->sendCancelPaymentMessage($eventPaymentTransfer);

        // Assert
        $this->tester->assertMessageWasSent(CancelPaymentTransfer::class);
        $this->tester->assertSentMessageProperties(
            CancelPaymentTransfer::class,
            ['amount' => 0, 'orderItemIds' => $sentItemIds],
        );
    }

    /**
     * @return void
     */
    public function testSendCancelPaymentMessageThrowsOrderNotFoundException(): void
    {
        // Arrange
        $this->mockSalesFacade([]);

        $eventPaymentTransfer = (new EventPaymentTransfer())
            ->setIdSalesOrder(1000);

        // Expect
        $this->expectException(OrderNotFoundException::class);

        // Act
        $this->tester->getFacade()->sendEventPaymentCancelReservationPending($eventPaymentTransfer);
    }

    /**
     * @return array<array>
     */
    public function orderItemsDataProviderForCancelSuccessProcess(): array
    {
        return [
            'single items with state new and full sended item ids' => [
                SalesPaymentBusinessTester::KEY_ORDER => [
                    OrderTransfer::ID_SALES_ORDER => 1000,
                    OrderTransfer::ITEMS => [
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 777,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                    ],
                    OrderTransfer::EXPENSES => [
                        [
                            ExpenseTransfer::SUM_GROSS_PRICE => 5,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 0,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => 15,
            ],
            'multiple items with state new and full sended item ids' => [
                SalesPaymentBusinessTester::KEY_ORDER => [
                    OrderTransfer::ID_SALES_ORDER => 1000,
                    OrderTransfer::ITEMS => [
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 777,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 888,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                    ],
                    OrderTransfer::EXPENSES => [
                        [
                            ExpenseTransfer::SUM_GROSS_PRICE => 5,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 0,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777, 888],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => 15,
            ],
            'multiple items with state new and partial sended item ids' => [
                SalesPaymentBusinessTester::KEY_ORDER => [
                    OrderTransfer::ID_SALES_ORDER => 1000,
                    OrderTransfer::ITEMS => [
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 777,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 888,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                    ],
                    OrderTransfer::EXPENSES => [
                        [
                            ExpenseTransfer::SUM_GROSS_PRICE => 5,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 0,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => 15,
            ],
        ];
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return void
     */
    protected function mockSalesFacade(array $seedData): void
    {
        $orderTransfer = null;

        if ($seedData) {
            $orderTransfer = $this->tester->getOrderTransfer($seedData);
        }

        $salesPaymentToSalesFacadeMock = $this->createMock(SalesPaymentToSalesFacadeInterface::class);
        $salesPaymentToSalesFacadeMock->method('findOrderByIdSalesOrder')->willReturn($orderTransfer);

        $this->tester->setDependency(SalesPaymentDependencyProvider::FACADE_SALES, $salesPaymentToSalesFacadeMock);
    }
}
