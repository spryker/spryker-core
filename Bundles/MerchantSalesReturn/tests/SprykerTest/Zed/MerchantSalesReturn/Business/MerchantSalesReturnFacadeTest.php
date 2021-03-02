<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesReturn\Business;

use ArrayObject;
use Codeception\Test\Unit;
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
    protected const TEST_MERCHANT_REFERENCE_1 = 'test-merchant-reference-1';
    protected const TEST_MERCHANT_REFERENCE_2 = 'test-merchant-reference-2';

    /**
     * @var \SprykerTest\Zed\MerchantSalesReturn\MerchantSalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPreCreateSetsMerchantOrderReferenceSuccessfully(): void
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
            $merchantOrderTransfer->getMerchantOrderReference(),
            $actualReturnTransfer->getMerchantSalesOrderReference()
        );
    }

    /**
     * @return void
     */
    public function testPreCreateReturnsResultWithoutMerchantOrderReferenceWhenOrderItemsIsNotValid(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);

        $returnTransfer = new ReturnTransfer();

        foreach ($saveOrderTransfer->getOrderItems() as $orderItem) {
            $orderItemTransfer = $this->tester->createItemTransfer($orderItem->getMerchantReference(), 324);
            $returnItemTransfer = (new ReturnItemTransfer())->setOrderItem($orderItemTransfer);

            $returnTransfer->addReturnItem($returnItemTransfer);
        }

        // Act
        $actualReturnTransfer = $this->tester
            ->getFacade()
            ->preCreate($returnTransfer);

        // Assert
        $this->assertNull($actualReturnTransfer->getMerchantSalesOrderReference());
    }

    /**
     * @return void
     */
    public function testValidateReturnReturnsSuccessfulReturnResponseTransfer(): void
    {
        // Arrange
        $returnCreateRequestTransfer = new ReturnCreateRequestTransfer();

        $itemTransfers = new ArrayObject();
        $itemTransfers->append($this->tester->createItemTransfer(self::TEST_MERCHANT_REFERENCE_1));
        $itemTransfers->append($this->tester->createItemTransfer(self::TEST_MERCHANT_REFERENCE_1));
        $itemTransfers->append($this->tester->createItemTransfer(self::TEST_MERCHANT_REFERENCE_1));

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->validateReturn($returnCreateRequestTransfer, $itemTransfers);

        $messageTransfers = $returnResponseTransfer->getMessages();

        // Assert
        $this->assertTrue($returnResponseTransfer->getIsSuccessful());
        $this->assertSame(0, $messageTransfers->count());
    }

    /**
     * @return void
     */
    public function testValidateReturnFailed(): void
    {
        // Arrange
        $returnCreateRequestTransfer = new ReturnCreateRequestTransfer();

        $itemTransfers = new ArrayObject();
        $itemTransfers->append($this->tester->createItemTransfer(self::TEST_MERCHANT_REFERENCE_1));
        $itemTransfers->append($this->tester->createItemTransfer(self::TEST_MERCHANT_REFERENCE_1));
        $itemTransfers->append($this->tester->createItemTransfer(self::TEST_MERCHANT_REFERENCE_2));

        // Act
        $returnResponseTransfer = $this->tester
            ->getFacade()
            ->validateReturn($returnCreateRequestTransfer, $itemTransfers);

        $messageTransfers = $returnResponseTransfer->getMessages();

        // Assert
        $this->assertFalse($returnResponseTransfer->getIsSuccessful());
        $this->assertSame(1, $messageTransfers->count());
    }
}
