<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPayment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CapturePaymentTransfer;
use Generated\Shared\Transfer\EventPaymentTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\SalesPayment\Business\Exception\EventExecutionForbiddenException;
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
 * @group SendCapturePaymentMessageSalesPaymentFacadeTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SalesPayment\SalesPaymentBusinessTester $tester
 */
class SendCapturePaymentMessageSalesPaymentFacadeTest extends Unit
{
    /**
     * @var \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected $salesPaymentFacade;

    /**
     * @dataProvider orderItemsDataProviderForCaptureSuccessProcess
     *
     * @param array $order
     * @param array $sentItemIds
     * @param int $expectedAmount
     *
     * @return void
     */
    public function testSendCapturePaymentMessageSuccess(array $order, array $sentItemIds, int $expectedAmount): void
    {
        // Arrange
        $this->mockSalesFacade($order);

        $eventPaymentTransfer = (new EventPaymentTransfer())
            ->setIdSalesOrder($order[OrderTransfer::ID_SALES_ORDER])
            ->setOrderItemIds($sentItemIds);

        // Act
        $this->tester->getFacade()->sendCapturePaymentMessage($eventPaymentTransfer);

        // Assert
        $this->tester->assertMessageWasSent(CapturePaymentTransfer::class);
        $this->tester->assertSentMessageProperties(
            CapturePaymentTransfer::class,
            ['amount' => $expectedAmount, 'orderItemIds' => $sentItemIds],
        );
    }

    /**
     * @dataProvider orderItemsDataProviderForCaptureFailureProcess
     *
     * @param array $order
     * @param array $sentItemIds
     *
     * @return void
     */
    public function testSendCapturePaymentMessageThrowsCommandExecutionException(array $order, array $sentItemIds): void
    {
        // Arrange
        $this->mockSalesFacade($order);

        $eventPaymentTransfer = (new EventPaymentTransfer())
            ->setIdSalesOrder($order[OrderTransfer::ID_SALES_ORDER])
            ->setOrderItemIds($sentItemIds);

        // Expect
        $this->expectException(EventExecutionForbiddenException::class);

        // Act
        $this->tester->getFacade()->sendCapturePaymentMessage($eventPaymentTransfer);
    }

    /**
     * @return void
     */
    public function testSendEventPaymentConfirmationPendingThrowOrderNotFoundException(): void
    {
        // Arrange
        $this->mockSalesFacade([]);

        $eventPaymentTransfer = (new EventPaymentTransfer())
            ->setIdSalesOrder(1000);

        // Expect
        $this->expectException(OrderNotFoundException::class);

        // Act
        $this->tester->getFacade()->sendEventPaymentConfirmationPending($eventPaymentTransfer);
        $this->tester->assertMessageWasNotSent(CapturePaymentTransfer::class);
    }

    /**
     * @return array<array>
     */
    public function orderItemsDataProviderForCaptureSuccessProcess(): array
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
                            ItemTransfer::STATE_HISTORY => [
                                [
                                    ItemStateTransfer::NAME => 'new',
                                ],
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
                            ExpenseTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 5,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 0,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => 15,
            ],
            'single items with state history payment confirmed and full sended item ids' => [
                SalesPaymentBusinessTester::KEY_ORDER => [
                    OrderTransfer::ID_SALES_ORDER => 1000,
                    OrderTransfer::ITEMS => [
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 777,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::STATE_HISTORY => [
                                [
                                    ItemStateTransfer::NAME => 'payment captured',
                                ],
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
                            ExpenseTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 5,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 0,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => 10,
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
                            ItemTransfer::STATE_HISTORY => [
                                [
                                    ItemStateTransfer::NAME => 'new',
                                ],
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 888,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 15,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::STATE_HISTORY => [
                                [
                                    ItemStateTransfer::NAME => 'new',
                                ],
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
                            ExpenseTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 5,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 0,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777, 888],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => 30,
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
                            ItemTransfer::STATE_HISTORY => [
                                [
                                    ItemStateTransfer::NAME => 'new',
                                ],
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 888,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 15,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::STATE_HISTORY => [
                                [
                                    ItemStateTransfer::NAME => 'new',
                                ],
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
                            ExpenseTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 5,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 0,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => 15,
            ],
            'multiple items with state history payment confirmed and full sended item ids' => [
                SalesPaymentBusinessTester::KEY_ORDER => [
                    OrderTransfer::ID_SALES_ORDER => 1000,
                    OrderTransfer::ITEMS => [
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 777,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::STATE_HISTORY => [
                                [
                                    ItemStateTransfer::NAME => 'payment captured',
                                ],
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 888,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 15,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::STATE_HISTORY => [
                                [
                                    ItemStateTransfer::NAME => 'payment captured',
                                ],
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
                            ExpenseTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 5,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 0,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777, 888],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => 25,
            ],
            'multiple items with state history payment confirmed and partial sended item ids' => [
                SalesPaymentBusinessTester::KEY_ORDER => [
                    OrderTransfer::ID_SALES_ORDER => 1000,
                    OrderTransfer::ITEMS => [
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 777,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::STATE_HISTORY => [
                                [
                                    ItemStateTransfer::NAME => 'payment captured',
                                ],
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 888,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 15,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'new',
                            ],
                            ItemTransfer::STATE_HISTORY => [
                                [
                                    ItemStateTransfer::NAME => 'payment captured',
                                ],
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
                            ExpenseTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 5,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 0,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => 10,
            ],
        ];
    }

    /**
     * @return array<array>
     */
    public function orderItemsDataProviderForCaptureFailureProcess(): array
    {
        return [
            'single items with state payment confirmation pending and full sended item ids' => [
                SalesPaymentBusinessTester::KEY_ORDER => [
                    OrderTransfer::ID_SALES_ORDER => 1000,
                    OrderTransfer::ITEMS => [
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 777,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'payment capture pending',
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777],
            ],
            'multiple items with state payment confirmation pending and full sended item ids' => [
                SalesPaymentBusinessTester::KEY_ORDER => [
                    OrderTransfer::ID_SALES_ORDER => 1000,
                    OrderTransfer::ITEMS => [
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 777,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'payment capture pending',
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 0,
                                ],
                            ],
                        ],
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 888,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 15,
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
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777, 888],
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
