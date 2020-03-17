<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrder\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
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
     * @return array
     */
    public function getMerchantOrderPositiveScenarioDataProvider(): array
    {
        return [
            'by id merchant order' => [
                'merchantOrderCriteriaDataKeys' => [
                    MerchantOrderCriteriaTransfer::ID_MERCHANT_ORDER,
                ],
                'merchantOrderItemsCount' => 0,
            ],
            'by id order and id merchant' => [
                'merchantOrderCriteriaDataKeys' => [
                    MerchantOrderCriteriaTransfer::ID_ORDER,
                    MerchantOrderCriteriaTransfer::ID_MERCHANT,
                ],
                'merchantOrderItemsCount' => 0,
            ],
            'by id order and merchant reference' => [
                'merchantOrderCriteriaDataKeys' => [
                    MerchantOrderCriteriaTransfer::ID_ORDER,
                    MerchantOrderCriteriaTransfer::MERCHANT_REFERENCE,
                ],
                'merchantOrderItemsCount' => 0,
            ],
            'by merchant order reference' => [
                'merchantOrderCriteriaDataKeys' => [
                    MerchantOrderCriteriaTransfer::MERCHANT_ORDER_REFERENCE,
                ],
                'merchantOrderItemsCount' => 0,
            ],
            'with items' => [
                'merchantOrderCriteriaDataKeys' => [
                    MerchantOrderCriteriaTransfer::ID_MERCHANT_ORDER,
                    MerchantOrderCriteriaTransfer::WITH_ITEMS,
                ],
                'merchantOrderItemsCount' => 1,
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
                'merchantOrderCriteriaData' => [
                    MerchantOrderCriteriaTransfer::ID_MERCHANT_ORDER => 0,
                ],
            ],
            'by id order and id merchant' => [
                'merchantOrderCriteriaData' => [
                    MerchantOrderCriteriaTransfer::ID_MERCHANT => 0,
                    MerchantOrderCriteriaTransfer::ID_ORDER => 0,
                ],
            ],
            'by id order and merchant reference' => [
                'merchantOrderCriteriaData' => [
                    MerchantOrderCriteriaTransfer::MERCHANT_REFERENCE => 'wrong_merchant_reference',
                    MerchantOrderCriteriaTransfer::ID_ORDER => 0,
                ],
            ],
            'by merchant order reference' => [
                'merchantOrderCriteriaData' => [
                    MerchantOrderCriteriaTransfer::MERCHANT_ORDER_REFERENCE => 'wrong_merchant_sales_order_reference',
                ],
            ],
        ];
    }
}
