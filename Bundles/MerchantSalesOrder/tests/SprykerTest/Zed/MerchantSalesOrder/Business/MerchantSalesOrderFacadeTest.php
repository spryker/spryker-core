<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrder\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantSalesOrder
 * @group Business
 * @group Facade
 * @group MerchantSalesOrderFacadeTest
 * Add your own group annotations below this line
 */
class MerchantSalesOrderFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE.
     */
    protected const VALID_SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';
    protected const INVALID_SHIPMENT_EXPENSE_TYPE = 'ANOTHER_EXPENSE_TYPE';
    protected const TEST_STATE_MACHINE = 'Test01';
    protected const TEST_MERCHANT_REFERENCE = 'test-merchant-reference';
    protected const TEST_SECOND_MERCHANT_REFERENCE = 'test-second-merchant-reference';

    /**
     * @var \SprykerTest\Zed\MerchantSalesOrder\MerchantSalesOrderBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateMerchantOrderCollectionReturnsFilledCollectionTransferWithCorrectData(): void
    {
        //Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);

        $merchantOrderReference = $this->tester->getMerchantOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $orderTransfer->setOrderReference($saveOrderTransfer->getOrderReference());
        $orderTransfer->setItems($saveOrderTransfer->getOrderItems());

        //Act
        $merchantOrderCollectionTransfer = $this->tester
            ->getFacade()
            ->createMerchantOrderCollection($orderTransfer);

        //Assert
        $this->assertCount(1, $merchantOrderCollectionTransfer->getMerchantOrders());
        /** @var \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer */
        $merchantOrderTransfer = $merchantOrderCollectionTransfer->getMerchantOrders()->offsetGet(0);
        $this->assertIsInt($merchantOrderTransfer->getIdMerchantOrder());
        $this->assertSame($merchantTransfer->getMerchantReference(), $merchantOrderTransfer->getMerchantReference());
        $this->assertSame($saveOrderTransfer->getIdSalesOrder(), $merchantOrderTransfer->getIdOrder());
        $this->assertSame($merchantOrderReference, $merchantOrderTransfer->getMerchantOrderReference());
        $this->assertInstanceOf(TotalsTransfer::class, $merchantOrderTransfer->getTotals());
        $this->assertCount(1, $merchantOrderTransfer->getMerchantOrderItems());
    }

    /**
     * @dataProvider getMerchantOrderPositiveScenarioDataProvider
     *
     * @param array $merchantOrderCriteriaKeys
     * @param int $merchantOrderItemsCount
     *
     * @return void
     */
    public function testGetMerchantOrderCollectionReturnsFilledCollectionTransferWithCorrectCriteria(
        array $merchantOrderCriteriaKeys,
        int $merchantOrderItemsCount
    ): void {
        //Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderReference = $this->tester->getMerchantOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantOrderReference
        );

        $merchantOrderCriteriaData = [
            MerchantOrderCriteriaTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderCriteriaTransfer::MERCHANT_ORDER_REFERENCE => $merchantOrderReference,
            MerchantOrderCriteriaTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            MerchantOrderCriteriaTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantOrderCriteriaTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantOrderCriteriaTransfer::WITH_ITEMS => true,
        ];
        $merchantOrderCriteriaData = array_intersect_key(
            $merchantOrderCriteriaData,
            array_flip($merchantOrderCriteriaKeys)
        );
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->fromArray($merchantOrderCriteriaData);

        //Act
        $merchantOrderCollectionTransfer = $this->tester
            ->getFacade()
            ->getMerchantOrderCollection($merchantOrderCriteriaTransfer);

        //Assert
        $this->assertCount(1, $merchantOrderCollectionTransfer->getMerchantOrders());
        /** @var \Generated\Shared\Transfer\MerchantOrderTransfer $foundMerchantOrderTransfer */
        $foundMerchantOrderTransfer = $merchantOrderCollectionTransfer->getMerchantOrders()->offsetGet(0);
        $this->assertSame(
            $merchantOrderTransfer->getIdMerchantOrder(),
            $foundMerchantOrderTransfer->getIdMerchantOrder()
        );
        $this->assertCount($merchantOrderItemsCount, $foundMerchantOrderTransfer->getMerchantOrderItems());
        $this->assertInstanceOf(TotalsTransfer::class, $foundMerchantOrderTransfer->getTotals());
    }

    /**
     * @dataProvider getMerchantOrderNegativeScenarioDataProvider
     *
     * @param array $merchantOrderCriteriaData
     *
     * @return void
     */
    public function testGetMerchantOrderCollectionReturnsEmptyCollectionTransferWithWrongCriteria(
        array $merchantOrderCriteriaData
    ): void {
        //Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderReference = $this->tester->getMerchantOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantOrderReference
        );

        $this->tester->haveMerchantOrderTotals($merchantOrderTransfer->getIdMerchantOrder());
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->fromArray($merchantOrderCriteriaData);

        //Act
        $merchantOrderCollectionTransfer = $this->tester
            ->getFacade()
            ->getMerchantOrderCollection($merchantOrderCriteriaTransfer);

        //Assert
        $this->assertCount(0, $merchantOrderCollectionTransfer->getMerchantOrders());
    }

    /**
     * @dataProvider getMerchantOrderPositiveScenarioDataProvider
     *
     * @param array $merchantOrderCriteriaDataKeys
     * @param int $merchantOrderItemsCount
     *
     * @return void
     */
    public function testFindMerchantOrderReturnsTransferWithCorrectCriteria(
        array $merchantOrderCriteriaDataKeys,
        int $merchantOrderItemsCount
    ): void {
        //Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderReference = $this->tester->getMerchantOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantOrderReference
        );

        $merchantOrderCriteriaData = [
            MerchantOrderCriteriaTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderCriteriaTransfer::MERCHANT_ORDER_REFERENCE => $merchantOrderReference,
            MerchantOrderCriteriaTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            MerchantOrderCriteriaTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantOrderCriteriaTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantOrderCriteriaTransfer::WITH_ITEMS => true,
        ];
        $merchantOrderCriteriaData = array_intersect_key(
            $merchantOrderCriteriaData,
            array_flip($merchantOrderCriteriaDataKeys)
        );
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->fromArray($merchantOrderCriteriaData);

        //Act
        $foundMerchantOrderTransfer = $this->tester
            ->getFacade()
            ->findMerchantOrder($merchantOrderCriteriaTransfer);

        //Assert
        $this->assertNotNull($foundMerchantOrderTransfer);
        $this->assertSame(
            $merchantOrderTransfer->getIdMerchantOrder(),
            $foundMerchantOrderTransfer->getIdMerchantOrder()
        );
        $this->assertCount($merchantOrderItemsCount, $foundMerchantOrderTransfer->getMerchantOrderItems());
        $this->assertInstanceOf(TotalsTransfer::class, $foundMerchantOrderTransfer->getTotals());
    }

    /**
     * @dataProvider getMerchantOrderNegativeScenarioDataProvider
     *
     * @param array $merchantOrderCriteriaData
     *
     * @return void
     */
    public function testFindMerchantOrderReturnsNullWithWrongCriteria(array $merchantOrderCriteriaData): void
    {
        //Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderReference = $this->tester->getMerchantOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $this->tester->createMerchantOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantOrderReference
        );

        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->fromArray($merchantOrderCriteriaData);

        //Act
        $foundMerchantOrderTransfer = $this->tester
            ->getFacade()
            ->findMerchantOrder($merchantOrderCriteriaTransfer);

        //Assert
        $this->assertNull($foundMerchantOrderTransfer);
    }

    /**
     * @return void
     */
    public function testExpandOrderItemWithMerchantReturnsUpdatedTransferWithCorrectData(): void
    {
        // Arrange
        $itemTransfer = $this->tester->getItemTransfer([
            ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ]);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        // Act
        $newSalesOrderItemEntityTransfer = $this->tester
            ->getFacade()
            ->expandOrderItemWithMerchant($salesOrderItemEntityTransfer, $itemTransfer);

        // Assert
        $this->assertSame(static::TEST_MERCHANT_REFERENCE, $newSalesOrderItemEntityTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemWithMerchantDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $itemTransfer = $this->tester->getItemTransfer();
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        // Act
        $newSalesOrderItemEntityTransfer = $this->tester
            ->getFacade()
            ->expandOrderItemWithMerchant($salesOrderItemEntityTransfer, $itemTransfer);

        // Assert
        $this->assertNull($newSalesOrderItemEntityTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testExpandShipmentExpenseWithMerchantReferenceReturnsUpdatedTransfer(): void
    {
        // Arrange
        $expenseTransfer = (new ExpenseTransfer())->setType(static::VALID_SHIPMENT_EXPENSE_TYPE);
        $itemTransfer = (new ItemTransfer())->setMerchantReference(static::TEST_MERCHANT_REFERENCE);
        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->addItem($itemTransfer);

        // Act
        $expenseTransfer = $this->tester
            ->getFacade()
            ->expandShipmentExpenseWithMerchantReference($expenseTransfer, $shipmentGroupTransfer);

        // Assert
        $this->assertSame($expenseTransfer->getMerchantReference(), $itemTransfer->getMerchantReference());
    }

    /**
     * @dataProvider getExpandShipmentExpenseWithMerchantReferenceNegativeScenarioDataProvider
     *
     * @param array $itemTransfersData
     * @param string $expenseType
     *
     * @return void
     */
    public function testExpandShipmentExpenseWithMerchantReferenceDoesNothingWithIncorrectData(
        array $itemTransfersData,
        string $expenseType
    ): void {
        // Arrange
        $expenseTransfer = (new ExpenseTransfer())->setType($expenseType);
        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        foreach ($itemTransfersData as $itemTransferData) {
            $shipmentGroupTransfer->addItem((new ItemTransfer())->fromArray($itemTransferData));
        }

        // Act
        $expenseTransfer = $this->tester
            ->getFacade()
            ->expandShipmentExpenseWithMerchantReference($expenseTransfer, $shipmentGroupTransfer);

        // Assert
        $this->assertNull($expenseTransfer->getMerchantReference());
    }

    /**
     * @return array
     */
    public function getExpandShipmentExpenseWithMerchantReferenceNegativeScenarioDataProvider(): array
    {
        return [
            'with incorrect expense type' => [
                [
                    [
                        ExpenseTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
                    ],
                ],
                static::INVALID_SHIPMENT_EXPENSE_TYPE,
            ],
            'with different merchant references' => [
                [
                    [
                        ExpenseTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
                    ],
                    [
                        ExpenseTransfer::MERCHANT_REFERENCE => static::TEST_SECOND_MERCHANT_REFERENCE,
                    ],
                ],
                static::VALID_SHIPMENT_EXPENSE_TYPE,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getMerchantOrderPositiveScenarioDataProvider(): array
    {
        return [
            'by id merchant order' => [
                [
                    MerchantOrderCriteriaTransfer::ID_MERCHANT_ORDER,
                ],
                0,
            ],
            'by id order and id merchant' => [
                [
                    MerchantOrderCriteriaTransfer::ID_ORDER,
                    MerchantOrderCriteriaTransfer::ID_MERCHANT,
                ],
                0,
            ],
            'by id order and merchant reference' => [
                [
                    MerchantOrderCriteriaTransfer::ID_ORDER,
                    MerchantOrderCriteriaTransfer::MERCHANT_REFERENCE,
                ],
                0,
            ],
            'by merchant order reference' => [
                [
                    MerchantOrderCriteriaTransfer::MERCHANT_ORDER_REFERENCE,
                ],
                0,
            ],
            'with items' => [
                [
                    MerchantOrderCriteriaTransfer::ID_MERCHANT_ORDER,
                    MerchantOrderCriteriaTransfer::WITH_ITEMS,
                ],
                1,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getMerchantOrderNegativeScenarioDataProvider(): array
    {
        return [
            'by id merchant order' => [
                [
                    MerchantOrderCriteriaTransfer::ID_MERCHANT_ORDER => 0,
                ],
            ],
            'by id order and id merchant' => [
                [
                    MerchantOrderCriteriaTransfer::ID_MERCHANT => 0,
                    MerchantOrderCriteriaTransfer::ID_ORDER => 0,
                ],
            ],
            'by id order and merchant reference' => [
                [
                    MerchantOrderCriteriaTransfer::MERCHANT_REFERENCE => 'wrong_merchant_reference',
                    MerchantOrderCriteriaTransfer::ID_ORDER => 0,
                ],
            ],
            'by merchant order reference' => [
                [
                    MerchantOrderCriteriaTransfer::MERCHANT_ORDER_REFERENCE => 'wrong_merchant_sales_order_reference',
                ],
            ],
        ];
    }
}
