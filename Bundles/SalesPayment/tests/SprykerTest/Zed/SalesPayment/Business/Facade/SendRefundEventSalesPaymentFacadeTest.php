<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPayment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventPaymentTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentRefundRequestedTransfer;
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
 * @group SendRefundEventSalesPaymentFacadeTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SalesPayment\SalesPaymentBusinessTester $tester
 */
class SendRefundEventSalesPaymentFacadeTest extends Unit
{
    /**
     * @var \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected $salesPaymentFacade;

    /**
     * @dataProvider orderItemsDataProviderForRefundSuccessProcess
     *
     * @param array $orderData
     * @param array $sentItemIds
     * @param int $expectedAmount
     *
     * @return void
     */
    public function testSendEventPaymentRefundPendingSuccess(array $orderData, array $sentItemIds, int $expectedAmount): void
    {
        // Arrange
        $this->mockSalesFacade($orderData);

        $eventPaymentTransfer = (new EventPaymentTransfer())
            ->setIdSalesOrder($orderData[OrderTransfer::ID_SALES_ORDER])
            ->setOrderItemIds($sentItemIds);

        // Act
        $this->tester->getFacade()->sendEventPaymentRefundPending($eventPaymentTransfer);

        // Assert
        $this->tester->assertMessageWasSent(PaymentRefundRequestedTransfer::class);
        $this->tester->assertSentMessageProperties(
            PaymentRefundRequestedTransfer::class,
            ['amount' => $expectedAmount, 'orderItemIds' => $sentItemIds],
        );
    }

    /**
     * @dataProvider orderItemsDataProviderForRefundFailureProcess
     *
     * @param array $orderData
     * @param array $sentItemIds
     *
     * @return void
     */
    public function testEventPaymentRefundPendingThrowCommandExecutionException(array $orderData, array $sentItemIds): void
    {
        // Arrange
        $this->mockSalesFacade($orderData);

        $eventPaymentTransfer = (new EventPaymentTransfer())
            ->setIdSalesOrder($orderData[OrderTransfer::ID_SALES_ORDER])
            ->setOrderItemIds($sentItemIds);

        // Expect
        $this->expectException(EventExecutionForbiddenException::class);

        // Act
        $this->tester->getFacade()->sendEventPaymentRefundPending($eventPaymentTransfer);
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
        $this->tester->getFacade()->sendEventPaymentRefundPending($eventPaymentTransfer);
    }

    /**
     * @return array<array>
     */
    public function orderItemsDataProviderForRefundSuccessProcess(): array
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
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 10,
                                ],
                            ],
                        ],
                    ],
                    OrderTransfer::EXPENSES => [
                        [
                            ExpenseTransfer::SUM_GROSS_PRICE => 0,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 5,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => -15,
            ],
            'multiple items with state payment new and partial sended item ids' => [
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
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 10,
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
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 15,
                                ],
                            ],
                        ],
                    ],
                    OrderTransfer::EXPENSES => [
                        [
                            ExpenseTransfer::SUM_GROSS_PRICE => 0,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 5,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [888],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => -15,
            ],
            'multiple items with state payment new and full sended item ids' => [
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
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 10,
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
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 15,
                                ],
                            ],
                        ],
                    ],
                    OrderTransfer::EXPENSES => [
                        [
                            ExpenseTransfer::SUM_GROSS_PRICE => 0,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 5,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777, 888],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => -30,
            ],
            'multiple items with state payment refunded and partial sended item ids' => [
                SalesPaymentBusinessTester::KEY_ORDER => [
                    OrderTransfer::ID_SALES_ORDER => 1000,
                    OrderTransfer::ITEMS => [
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 777,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'payment refunded',
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 10,
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
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 15,
                                ],
                            ],
                        ],
                    ],
                    OrderTransfer::EXPENSES => [
                        [
                            ExpenseTransfer::SUM_GROSS_PRICE => 0,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 5,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [888],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => -20,
            ],
            'single items with state confirmed and partial sended item ids and multiple expanses' => [
                SalesPaymentBusinessTester::KEY_ORDER => [
                    OrderTransfer::ID_SALES_ORDER => 1000,
                    OrderTransfer::ITEMS => [
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 777,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'confirmed',
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 10,
                                ],
                            ],
                        ],
                        [
                            ItemTransfer::ID_SALES_ORDER_ITEM => 888,
                            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10,
                            ItemTransfer::STATE => [
                                ItemStateTransfer::NAME => 'confirmed',
                            ],
                            ItemTransfer::PRODUCT_OPTIONS => [
                                [
                                    ProductOptionTransfer::REFUNDABLE_AMOUNT => 15,
                                ],
                            ],
                        ],
                    ],
                    OrderTransfer::EXPENSES => [
                        [
                            ExpenseTransfer::SUM_GROSS_PRICE => 0,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 5,
                        ],
                        [
                            ExpenseTransfer::SUM_GROSS_PRICE => 0,
                            ExpenseTransfer::REFUNDABLE_AMOUNT => 5,
                        ],
                    ],
                ],
                SalesPaymentBusinessTester::KEY_SENT_ITEM_IDS => [777, 888],
                SalesPaymentBusinessTester::KEY_EXPECTED_AMOUNT => -35,
            ],
        ];
    }

    /**
     * @return array<array>
     */
    public function orderItemsDataProviderForRefundFailureProcess(): array
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
                                ItemStateTransfer::NAME => 'payment confirmation pending',
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
                                ItemStateTransfer::NAME => 'payment confirmation pending',
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
