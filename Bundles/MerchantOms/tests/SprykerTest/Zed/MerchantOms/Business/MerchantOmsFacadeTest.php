<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantOms
 * @group Business
 * @group Facade
 * @group MerchantOmsFacadeTest
 *
 * Add your own group annotations below this line
 */
class MerchantOmsFacadeTest extends Unit
{
    protected const TEST_STATE_MACHINE = 'Test01';

    /**
     * @var \SprykerTest\Zed\MerchantOms\MerchantOmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetStateMachineItemsByStateIdsReturnsCorrectData(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $saveOrderTransfer = $this->tester->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderTransfer = $this->tester->haveMerchantOrder([MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder()]);

        $processEntity = $this->tester->haveStateMachineProcess();

        $stateEntity = $this->tester->createStateMachineItemState($processEntity);

        $merchantOrderItemTransfer = $this->tester->haveMerchantOrderItem([
            MerchantOrderItemTransfer::FK_STATE_MACHINE_ITEM_STATE => $stateEntity->getIdStateMachineItemState(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
        ]);
        $merchantOrderItemCriteriaTransfer = new MerchantOrderItemCriteriaTransfer();
        $merchantOrderItemCriteriaTransfer->addStateMachineItemStateId($stateEntity->getIdStateMachineItemState());

        // Act

        $stateMachineItemTransfers = $this->tester->getFacade()->getStateMachineItemsByCriteria($merchantOrderItemCriteriaTransfer);
        $stateMachineItemTransfer = $stateMachineItemTransfers[0] ?? null;

        // Assert
        $this->assertCount(1, $stateMachineItemTransfers);
        $this->assertInstanceOf(StateMachineItemTransfer::class, $stateMachineItemTransfer);
        $this->assertSame($stateMachineItemTransfer->getIdItemState(), $stateEntity->getIdStateMachineItemState());
        $this->assertSame($stateMachineItemTransfer->getIdentifier(), $merchantOrderItemTransfer->getIdMerchantOrderItem());
    }
}
