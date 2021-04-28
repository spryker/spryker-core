<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesReturn\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantSalesReturn
 * @group Business
 * @group Facade
 * @group MerchantSalesReturnFacadeTest
 * Add your own group annotations below this line
 */
class MerchantSalesReturnFacadeTest extends Unit
{
    protected const TEST_STATE_MACHINE = 'Test01';
    protected const TEST_MERCHANT_SALES_ORDER_REFERENCE_1 = 'test-merchant-sales-order-reference-1';
    protected const TEST_MERCHANT_REFERENCE_1 = 'test-merchant-reference-1';
    protected const TEST_MERCHANT_REFERENCE_2 = 'test-merchant-reference-2';
    protected const TEST_UUID = '3b6743a7-ad62-3779-8648-e0156e51a628';
    protected const NOT_EXISTING_ORDER_ITEM_UUID = 'non-existing-order-item-uuid';

    /**
     * @var \SprykerTest\Zed\MerchantSalesReturn\MerchantSalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPreCreateSetsMerchantReferenceSuccessfully(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);

        $merchantOrderTransfer = $this->tester->createMerchantOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer
        );

        $returnTransfer = new ReturnTransfer();

        foreach ($saveOrderTransfer->getOrderItems() as $orderItem) {
            $returnItemTransfer = new ReturnItemTransfer();
            $returnItemTransfer->setOrderItem($orderItem);
            $returnTransfer->addReturnItem($returnItemTransfer);
        }

        // Act
        $actualReturnTransfer = $this->tester
            ->getFacade()
            ->preCreate($returnTransfer);

        // Assert
        $this->assertSame(
            $merchantOrderTransfer->getMerchantReference(),
            $actualReturnTransfer->getMerchantReference()
        );
    }

    /**
     * @return void
     */
    public function testPreCreateDoesNotSetMerchantReferenceForReturnWithNonMerchantItems(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);

        $returnTransfer = new ReturnTransfer();

        foreach ($saveOrderTransfer->getOrderItems() as $orderItem) {
            $orderItemTransfer = (new ItemTransfer())
                ->setUuid(static::TEST_UUID);

            $returnItemTransfer = new ReturnItemTransfer();
            $returnItemTransfer->setOrderItem($orderItemTransfer);
            $returnTransfer->addReturnItem($returnItemTransfer);
        }

        // Act
        $actualReturnTransfer = $this->tester
            ->getFacade()
            ->preCreate($returnTransfer);

        // Assert
        $this->assertNull($actualReturnTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testValidateReturnsSuccessfulReturnResponse(): void
    {
        // Arrange
        $returnCreateRequestTransfer = new ReturnCreateRequestTransfer();
        $returnCreateRequestTransfer->setReturnItems(new ArrayObject([
            $this->tester->createReturnItem(static::TEST_MERCHANT_REFERENCE_1, 1),
            $this->tester->createReturnItem(static::TEST_MERCHANT_REFERENCE_1, 1),
            $this->tester->createReturnItem(static::TEST_MERCHANT_REFERENCE_1, 2),
        ]));

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->validateReturn($returnCreateRequestTransfer);

        $messageTransfers = $returnResponseTransfer->getMessages();

        // Assert
        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
        $this->assertSame(0, $messageTransfers->count());
    }

    /**
     * @return void
     */
    public function testValidateReturnFailedWithDifferentMerchantReferences(): void
    {
        // Arrange
        $returnCreateRequestTransfer = new ReturnCreateRequestTransfer();
        $returnCreateRequestTransfer->setReturnItems(new ArrayObject([
            $this->tester->createReturnItem(static::TEST_MERCHANT_REFERENCE_1, 1),
            $this->tester->createReturnItem(static::TEST_MERCHANT_REFERENCE_2, 1),
            $this->tester->createReturnItem(static::TEST_MERCHANT_REFERENCE_1, 1),
        ]));

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->validateReturn($returnCreateRequestTransfer);

        $messageTransfers = $returnResponseTransfer->getMessages();

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertSame(1, $messageTransfers->count());
    }

    /**
     * @return void
     */
    public function testExpandSetsMerchantSuccessfully(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $saveOrderTransfer = $this->tester
            ->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);

        $merchantOrderTransfer = $this->tester->createMerchantOrderWithRelatedData(
            $saveOrderTransfer,
            $merchantTransfer
        );

        $returnItemTransfers = [];

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $returnItemTransfers[] = (new ReturnItemTransfer())->setOrderItem($merchantOrderItemTransfer->getOrderItem());
        }

        $returnTransfer = (new ReturnTransfer())
            ->setReturnItems(new ArrayObject($returnItemTransfers));

        // Act
        $actualReturnTransfer = $this->tester
            ->getFacade()
            ->expand($returnTransfer);

        // Assert
        $this->assertCount(1, $actualReturnTransfer->getMerchantOrders());
    }

    /**
     * @return void
     */
    public function testExpandDoesNotSetMerchantOrdersForNonMerchantReturn(): void
    {
        // Arrange
        $returnTransfer = (new ReturnTransfer())
            ->addReturnItem(
                (new ReturnItemTransfer())
                    ->setOrderItem(
                        (new ItemTransfer())->setUuid(static::NOT_EXISTING_ORDER_ITEM_UUID)
                    )
            );

        // Act
        $actualReturnTransfer = $this->tester
            ->getFacade()
            ->expand($returnTransfer);

        // Assert
        $this->assertCount(0, $actualReturnTransfer->getMerchantOrders());
    }
}
