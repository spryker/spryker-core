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
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
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
    public function testCreateMerchantSalesOrdersReturnsFilledCollectionTransferWithCorrectData(): void
    {
        //Arrange
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => 'test-merchant-reference',
        ]);
        $saveOrderTransfer = $this->getSaveOrderTransfer($merchantTransfer);

        $merchantSalesOrderReference = $this->getMerchantSalesOrderReference(
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
            ->createMerchantSalesOrders($orderTransfer);

        //Assert
        $this->assertCount(1, $merchantOrderCollectionTransfer->getOrders());
        /** @var \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer */
        $merchantOrderTransfer = $merchantOrderCollectionTransfer->getOrders()->offsetGet(0);
        $this->assertIsInt($merchantOrderTransfer->getIdMerchantSalesOrder());
        $this->assertEquals($merchantTransfer->getMerchantReference(), $merchantOrderTransfer->getMerchantReference());
        $this->assertEquals($saveOrderTransfer->getIdSalesOrder(), $merchantOrderTransfer->getIdSalesOrder());
        $this->assertEquals($merchantSalesOrderReference, $merchantOrderTransfer->getMerchantSalesOrderReference());
        $this->assertInstanceOf(TotalsTransfer::class, $merchantOrderTransfer->getTotals());
        $this->assertEquals(1, $merchantOrderTransfer->getMerchantOrderItems()->count());
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

        $merchantSalesOrderReference = $this->getMerchantSalesOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->createMerchantSalesOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantSalesOrderReference
        );

        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->setIdMerchantSalesOrder($merchantOrderTransfer->getIdMerchantSalesOrder())
            ->setMerchantSalesOrderReference($merchantSalesOrderReference)
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setIdMerchant($merchantTransfer->getIdMerchant());
        $merchantCriteriaFilterTransfer->fromArray($merchantOrderCriteriaFilterData, true);

        //Act
        $merchantOrderCollectionTransfer = $this->tester
            ->getFacade()
            ->getMerchantOrderCollection($merchantCriteriaFilterTransfer);

        //Assert
        $this->assertCount(1, $merchantOrderCollectionTransfer->getOrders());
        /** @var \Generated\Shared\Transfer\MerchantOrderTransfer $foundMerchantOrderTransfer */
        $foundMerchantOrderTransfer = $merchantOrderCollectionTransfer->getOrders()->offsetGet(0);
        $this->assertEquals(
            $merchantOrderTransfer->getIdMerchantSalesOrder(),
            $foundMerchantOrderTransfer->getIdMerchantSalesOrder()
        );
        $this->assertEquals($merchantOrderItemsCount, $foundMerchantOrderTransfer->getMerchantOrderItems()->count());
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

        $merchantSalesOrderReference = $this->getMerchantSalesOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->createMerchantSalesOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantSalesOrderReference
        );

        $this->tester->haveMerchantOrderTotals([
            TotalsTransfer::ID_MERCHANT_SALES_ORDER => $merchantOrderTransfer->getIdMerchantSalesOrder(),
        ]);

        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->setIdMerchantSalesOrder($merchantOrderTransfer->getIdMerchantSalesOrder())
            ->setMerchantSalesOrderReference($merchantSalesOrderReference)
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setIdMerchant($merchantTransfer->getIdMerchant());
        $merchantCriteriaFilterTransfer->fromArray($merchantOrderCriteriaFilterData, true);

        //Act
        $merchantOrderCollectionTransfer = $this->tester
            ->getFacade()
            ->getMerchantOrderCollection($merchantCriteriaFilterTransfer);

        //Assert
        $this->assertCount(0, $merchantOrderCollectionTransfer->getOrders());
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

        $merchantSalesOrderReference = $this->getMerchantSalesOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->createMerchantSalesOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantSalesOrderReference
        );

        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->setIdMerchantSalesOrder($merchantOrderTransfer->getIdMerchantSalesOrder())
            ->setMerchantSalesOrderReference($merchantSalesOrderReference)
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setIdMerchant($merchantTransfer->getIdMerchant());
        $merchantCriteriaFilterTransfer->fromArray($merchantOrderCriteriaFilterData, true);

        //Act
        $foundMerchantOrderTransfer = $this->tester
            ->getFacade()
            ->findMerchantOrder($merchantCriteriaFilterTransfer);

        //Assert
        $this->assertNotNull($foundMerchantOrderTransfer);
        $this->assertEquals(
            $merchantOrderTransfer->getIdMerchantSalesOrder(),
            $foundMerchantOrderTransfer->getIdMerchantSalesOrder()
        );
        $this->assertEquals($merchantOrderItemsCount, $foundMerchantOrderTransfer->getMerchantOrderItems()->count());
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

        $merchantSalesOrderReference = $this->getMerchantSalesOrderReference(
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
        $merchantOrderTransfer = $this->createMerchantSalesOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer,
            $itemTransfer,
            $merchantSalesOrderReference
        );

        $merchantCriteriaFilterTransfer = (new MerchantOrderCriteriaFilterTransfer())
            ->setIdMerchantSalesOrder($merchantOrderTransfer->getIdMerchantSalesOrder())
            ->setMerchantSalesOrderReference($merchantSalesOrderReference)
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
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
     * @param string $orderReference
     * @param string $merchantReference
     *
     * @return string
     */
    protected function getMerchantSalesOrderReference(string $orderReference, string $merchantReference): string
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
     * @param string $merchantSalesOrderReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function createMerchantSalesOrderWithRelatedData(
        SaveOrderTransfer $saveOrderTransfer,
        MerchantTransfer $merchantTransfer,
        ItemTransfer $itemTransfer,
        string $merchantSalesOrderReference
    ): MerchantOrderTransfer {
        $merchantOrderTransfer = $this->tester->haveMerchantOrder([
            MerchantOrderTransfer::MERCHANT_SALES_ORDER_REFERENCE => $merchantSalesOrderReference,
            MerchantOrderTransfer::ID_SALES_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]);

        $this->tester->haveMerchantOrderItem([
            MerchantOrderItemTransfer::ID_SALES_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
            MerchantOrderItemTransfer::ID_MERCHANT_SALES_ORDER => $merchantOrderTransfer->getIdMerchantSalesOrder(),
        ]);

        $this->tester->haveMerchantOrderTotals([
            TotalsTransfer::ID_MERCHANT_SALES_ORDER => $merchantOrderTransfer->getIdMerchantSalesOrder(),
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
                    MerchantOrderCriteriaFilterTransfer::ID_SALES_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_SALES_ORDER_REFERENCE => null,
                ],
                0,
            ],
            'by id sales order and id merchant' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_SALES_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_SALES_ORDER_REFERENCE => null,
                ],
                0,
            ],
            'by id sales order and merchant reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_SALES_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_SALES_ORDER_REFERENCE => null,
                ],
                0,
            ],
            'by merchant sales order reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::ID_SALES_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_SALES_ORDER_REFERENCE => null,
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
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_SALES_ORDER => 0,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::ID_SALES_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_SALES_ORDER_REFERENCE => null,
                ],
            ],
            'by id sales order and id merchant' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_SALES_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => 0,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::ID_SALES_ORDER => 0,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_SALES_ORDER_REFERENCE => null,
                ],
            ],
            'by id sales order and merchant reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_SALES_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => 'wrong_merchant_reference',
                    MerchantOrderCriteriaFilterTransfer::ID_SALES_ORDER => 0,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_SALES_ORDER_REFERENCE => null,
                ],
            ],
            'by merchant sales order reference' => [
                [
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT_SALES_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::ID_MERCHANT => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_REFERENCE => null,
                    MerchantOrderCriteriaFilterTransfer::ID_SALES_ORDER => null,
                    MerchantOrderCriteriaFilterTransfer::MERCHANT_SALES_ORDER_REFERENCE => 'wrong_merchant_sales_order_reference',
                ],
            ],
        ];
    }
}
