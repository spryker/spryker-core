<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrder\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantSalesOrderTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

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
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE]);
    }

    /**
     * @return void
     */
    public function testCreateMerchantSalesOrder(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([
            ItemTransfer::UNIT_PRICE => 100,
            ItemTransfer::SUM_PRICE => 100,
        ], static::TEST_STATE_MACHINE);

        $merchantTransfer = $this->tester->haveMerchant();

        $merchantSalesOrderTransfer = new MerchantSalesOrderTransfer();
        $merchantSalesOrderTransfer->setMerchantReference($merchantTransfer->getMerchantKey());
        $merchantSalesOrderTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $merchantSalesOrderTransfer->setOrderReference($saveOrderTransfer->getOrderReference());

        // Act
        $merchantSalesOrderTransfer = $this->tester
            ->getFacade()
            ->createMerchantSalesOrder($merchantSalesOrderTransfer);

        // Assert
        $this->assertIsInt($merchantSalesOrderTransfer->getIdMerchantSalesOrder());
        $this->assertEquals($merchantSalesOrderTransfer->getMerchantReference(), $merchantTransfer->getMerchantKey());
        $this->assertEquals($merchantSalesOrderTransfer->getFkSalesOrder(), $saveOrderTransfer->getIdSalesOrder());
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
}
