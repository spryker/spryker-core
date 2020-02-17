<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrder\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
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
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => 'test-merchant-reference',
        ]);
        $saveOrderTransfer = $this->getSaveOrderTransfer($merchantTransfer);

        $merchantOrderReference = $this->getMerchantOrderReference(
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
     * @param array $merchantOrderCriteriaFilterData
     * @param int $merchantOrderItemsCount
     *
     * @return void
     */
    public function testGetMerchantOrderCollectionReturnsFilledCollectionTransferWithCorrectCriteria(
        array $merchantOrderCriteriaFilterData,
        int $merchantOrderItemsCount
    ): void {
        //Arrange
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => 'test-merchant-reference',
        ]);
        $saveOrderTransfer = $this->getSaveOrderTransfer($merchantTransfer);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderReference = $this->getMerchantOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->createMerchantSalesOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantOrderReference
        );

        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->setIdMerchantOrder($merchantOrderTransfer->getIdMerchantOrder())
            ->setMerchantOrderReference($merchantOrderReference)
            ->setIdOrder($saveOrderTransfer->getIdSalesOrder())
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setIdMerchant($merchantTransfer->getIdMerchant());
        $merchantCriteriaFilterTransfer->fromArray($merchantOrderCriteriaFilterData, true);

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
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => 'test-merchant-reference',
        ]);
        $saveOrderTransfer = $this->getSaveOrderTransfer($merchantTransfer);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderReference = $this->getMerchantOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->createMerchantSalesOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantOrderReference
        );

        $this->tester->haveMerchantOrderTotals([
            TotalsTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
        ]);

        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->setIdMerchantOrder($merchantOrderTransfer->getIdMerchantOrder())
            ->setMerchantOrderReference($merchantOrderReference)
            ->setIdOrder($saveOrderTransfer->getIdSalesOrder())
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setIdMerchant($merchantTransfer->getIdMerchant());
        $merchantCriteriaFilterTransfer->fromArray($merchantOrderCriteriaFilterData, true);

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
     * @param array $merchantOrderCriteriaFilterData
     * @param int $merchantOrderItemsCount
     *
     * @return void
     */
    public function testFindMerchantOrderReturnsTransferWithCorrectCriteria(
        array $merchantOrderCriteriaFilterData,
        int $merchantOrderItemsCount
    ): void {
        //Arrange
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => 'test-merchant-reference',
        ]);
        $saveOrderTransfer = $this->getSaveOrderTransfer($merchantTransfer);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderReference = $this->getMerchantOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->createMerchantSalesOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantOrderReference
        );

        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->setIdMerchantOrder($merchantOrderTransfer->getIdMerchantOrder())
            ->setMerchantOrderReference($merchantOrderReference)
            ->setIdOrder($saveOrderTransfer->getIdSalesOrder())
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setIdMerchant($merchantTransfer->getIdMerchant());
        $merchantCriteriaFilterTransfer->fromArray($merchantOrderCriteriaFilterData, true);

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
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => 'test-merchant-reference',
        ]);
        $saveOrderTransfer = $this->getSaveOrderTransfer($merchantTransfer);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderReference = $this->getMerchantOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->createMerchantSalesOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantOrderReference
        );

        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->setIdMerchantOrder($merchantOrderTransfer->getIdMerchantOrder())
            ->setMerchantOrderReference($merchantOrderReference)
            ->setIdOrder($saveOrderTransfer->getIdSalesOrder())
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setIdMerchant($merchantTransfer->getIdMerchant());
        $merchantCriteriaFilterTransfer->fromArray($merchantOrderCriteriaFilterData, true);

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
        $merchantReference = 'test-merchant-reference';
        $itemTransfer = $this->getItemTransfer([
            ItemTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        // Act
        $newSalesOrderItemEntityTransfer = $this->tester
            ->getFacade()
            ->expandOrderItemWithMerchant($salesOrderItemEntityTransfer, $itemTransfer);

        // Assert
        $this->assertEquals($newSalesOrderItemEntityTransfer->getMerchantReference(), $merchantReference);
    }

    /**
     * @return void
     */
    public function testExpandOrderItemWithMerchantDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $itemTransfer = $this->getItemTransfer();
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        // Act
        $newSalesOrderItemEntityTransfer = $this->tester
            ->getFacade()
            ->expandOrderItemWithMerchant($salesOrderItemEntityTransfer, $itemTransfer);

        // Assert
        $this->assertNull($newSalesOrderItemEntityTransfer->getMerchantReference());
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    protected function getItemTransfer(array $seedData = []): ItemTransfer
    {
        return (new ItemBuilder($seedData))->build();
    }

    /**
     * @param string $orderReference
     * @param string $merchantReference
     *
     * @return string
     */
    protected function getMerchantOrderReference(string $orderReference, string $merchantReference): string
    {
        return sprintf(
            '%s--%s',
            $orderReference,
            $merchantReference
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function getSaveOrderTransfer(MerchantTransfer $merchantTransfer): SaveOrderTransfer
    {
        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE]);

        return $this->tester->haveOrder([
            ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ItemTransfer::UNIT_PRICE => 100,
            ItemTransfer::SUM_PRICE => 100,
        ], static::TEST_STATE_MACHINE);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $merchantOrderReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function createMerchantSalesOrderWithRelatedData(
        SaveOrderTransfer $saveOrderTransfer,
        MerchantTransfer $merchantTransfer,
        ItemTransfer $itemTransfer,
        string $merchantOrderReference
    ): MerchantOrderTransfer {
        $merchantOrderTransfer = $this->tester->haveMerchantOrder([
            MerchantOrderTransfer::MERCHANT_ORDER_REFERENCE => $merchantOrderReference,
            MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]);

        $this->tester->haveMerchantOrderItem([
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
        ]);

        $this->tester->haveMerchantOrderTotals([
            TotalsTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
        ]);

        return $merchantOrderTransfer;
    }

    /**
     * @return array
     */
    public function getMerchantOrderPositiveScenarioDataProvider(): array
    {
        return [
            'by id merchant sales order' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::ID_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => null,
                ],
                0,
            ],
            'by id sales order and id merchant' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => null,
                ],
                0,
            ],
            'by id sales order and merchant reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => null,
                ],
                0,
            ],
            'by merchant sales order reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::ID_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => null,
                ],
                0,
            ],
            'with items' => [
                [
                    MerchantOrderCriteriaFilterTransfer::WITH_ITEMS => true,
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
            'by id merchant sales order' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER => 0,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::ID_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => null,
                ],
            ],
            'by id sales order and id merchant' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => 0,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::ID_ORDER => 0,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => null,
                ],
            ],
            'by id sales order and merchant reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => 'wrong_merchant_reference',
                    MerchantOrderCriteriaFilterTransfer::ID_ORDER => 0,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => null,
                ],
            ],
            'by merchant sales order reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::ID_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_ORDER_REFERENCE => 'wrong_merchant_sales_order_reference',
                ],
            ],
        ];
    }
}
