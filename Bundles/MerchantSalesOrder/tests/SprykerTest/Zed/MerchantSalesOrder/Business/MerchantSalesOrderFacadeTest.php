<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrder\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
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
    protected const TEST_STATE_MACHINE = 'Test01';
    protected const TEST_MERCHANT_REFERENCE = 'test-merchant-reference';
    protected const TEST_MERCHANT_ORDER_ITEM_ID = 1;

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
     * @param array $merchantOrderCriteriaFilterKeys
     * @param int $merchantOrderItemsCount
     *
     * @return void
     */
    public function testGetMerchantOrderCollectionReturnsFilledCollectionTransferWithCorrectCriteria(
        array $merchantOrderCriteriaFilterKeys,
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

        $merchantOrderCriteriaFilterData = [
            MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => $merchantOrderReference,
            MerchantOrderCriteriaFilterTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantOrderCriteriaFilterTransfer::WITH_ITEMS => true,
        ];
        $merchantOrderCriteriaFilterData = array_intersect_key(
            $merchantOrderCriteriaFilterData,
            array_flip($merchantOrderCriteriaFilterKeys)
        );
        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->fromArray($merchantOrderCriteriaFilterData);

        //Act
        $merchantOrderCollectionTransfer = $this->tester
            ->getFacade()
            ->getMerchantOrderCollection($merchantCriteriaFilterTransfer);

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
     * @param array $merchantOrderCriteriaFilterData
     *
     * @return void
     */
    public function testGetMerchantOrderCollectionReturnsEmptyCollectionTransferWithWrongCriteria(
        array $merchantOrderCriteriaFilterData
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

        $this->tester->haveMerchantOrderTotals([
            TotalsTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
        ]);
        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->fromArray($merchantOrderCriteriaFilterData);

        //Act
        $merchantOrderCollectionTransfer = $this->tester
            ->getFacade()
            ->getMerchantOrderCollection($merchantCriteriaFilterTransfer);

        //Assert
        $this->assertCount(0, $merchantOrderCollectionTransfer->getMerchantOrders());
    }

    /**
     * @dataProvider getMerchantOrderPositiveScenarioDataProvider
     *
     * @param array $merchantOrderCriteriaFilterDataKeys
     * @param int $merchantOrderItemsCount
     *
     * @return void
     */
    public function testFindMerchantOrderReturnsTransferWithCorrectCriteria(
        array $merchantOrderCriteriaFilterDataKeys,
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

        $merchantOrderCriteriaFilterData = [
            MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => $merchantOrderReference,
            MerchantOrderCriteriaFilterTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantOrderCriteriaFilterTransfer::WITH_ITEMS => true,
        ];
        $merchantOrderCriteriaFilterData = array_intersect_key(
            $merchantOrderCriteriaFilterData,
            array_flip($merchantOrderCriteriaFilterDataKeys)
        );
        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->fromArray($merchantOrderCriteriaFilterData);

        //Act
        $foundMerchantOrderTransfer = $this->tester
            ->getFacade()
            ->findMerchantOrder($merchantCriteriaFilterTransfer);

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
     * @param array $merchantOrderCriteriaFilterData
     *
     * @return void
     */
    public function testFindMerchantOrderReturnsNullWithWrongCriteria(array $merchantOrderCriteriaFilterData): void
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

        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->fromArray($merchantOrderCriteriaFilterData);

        //Act
        $foundMerchantOrderTransfer = $this->tester
            ->getFacade()
            ->findMerchantOrder($merchantCriteriaFilterTransfer);

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
    public function testUpdateMerchantOrderItemReturnsCorrectUpdatedTransfer(): void
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
        $merchantOrderTransfer = $this->tester->createMerchantOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantOrderReference
        );
        $merchantOrderItemTransfer = $merchantOrderTransfer->getMerchantOrderItems()->offsetGet(0);
        $secondOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        $secondOrderItemTransfer = $secondOrderTransfer->getOrderItems()->offsetGet(0);
        $merchantOrderItemTransfer->setIdOrderItem($secondOrderItemTransfer->getIdSalesOrderItem());

        //Act
        $merchantOrderItemTransferResponseTransfer = $this->tester
            ->getFacade()
            ->updateMerchantOrderItem($merchantOrderItemTransfer);

        //Assert
        $this->assertSame($secondOrderItemTransfer->getIdSalesOrderItem(), $merchantOrderItemTransferResponseTransfer->getMerchantOrderItem()->getIdOrderItem());
        $this->assertTrue($merchantOrderItemTransferResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantOrderItemReturnsExceptionWhenMerchantOrderItemNotExists(): void
    {
        //Arrange
        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())
            ->setIdMerchantOrderItem(static::TEST_MERCHANT_ORDER_ITEM_ID);

        //Act
        $merchantOrderItemTransferResponseTransfer = $this->tester->getFacade()->updateMerchantOrderItem($merchantOrderItemTransfer);

        //Assert
        $this->assertFalse($merchantOrderItemTransferResponseTransfer->getIsSuccessful());
    }

    /**
     * @return array
     */
    public function getMerchantOrderPositiveScenarioDataProvider(): array
    {
        return [
            'by id merchant order' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER,
                ],
                0,
            ],
            'by id order and id merchant' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_ORDER,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT,
                ],
                0,
            ],
            'by id order and merchant reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_ORDER,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE,
                ],
                0,
            ],
            'by merchant order reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE,
                ],
                0,
            ],
            'with items' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER,
                    MerchantOrderCriteriaFilterTransfer::WITH_ITEMS,
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
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER => 0,
                ],
            ],
            'by id order and id merchant' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => 0,
                    MerchantOrderCriteriaFilterTransfer::ID_ORDER => 0,
                ],
            ],
            'by id order and merchant reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => 'wrong_merchant_reference',
                    MerchantOrderCriteriaFilterTransfer::ID_ORDER => 0,
                ],
            ],
            'by merchant order reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => 'wrong_merchant_sales_order_reference',
                ],
            ],
        ];
    }
}
