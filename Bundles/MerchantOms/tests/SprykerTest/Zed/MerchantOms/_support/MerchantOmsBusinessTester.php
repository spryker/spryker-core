<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms;

use Codeception\Actor;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StateMachineItemStateTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantOmsBusinessTester extends Actor
{
    use _generated\MerchantOmsBusinessTesterActions;

    protected const TEST_STATE_MACHINE = 'Test01';

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param string $stateMachine
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function getSaveOrderTransfer(MerchantTransfer $merchantTransfer, string $stateMachine): SaveOrderTransfer
    {
        $this->configureTestStateMachine([$stateMachine]);

        return $this->haveOrder([
            ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ItemTransfer::UNIT_PRICE => 100,
            ItemTransfer::SUM_PRICE => 100,
        ], $stateMachine);
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function createMerchantOrderWithItems(): MerchantOrderTransfer
    {
        $merchantTransfer = $this->haveMerchant();
        $saveOrderTransfer = $this->getSaveOrderTransfer($merchantTransfer, static::TEST_STATE_MACHINE);

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $saveOrderTransfer->getOrderItems()->offsetGet(0);

        $merchantOrderTransfer = $this->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder(),
        ]);

        $stateMachineProcessEntity = $this->haveStateMachineProcess();
        $stateMachineItemState = $this->haveStateMachineItemState([
            StateMachineItemStateTransfer::FK_STATE_MACHINE_PROCESS => $stateMachineProcessEntity->getIdStateMachineProcess(),
        ]);

        $merchantOrderItemTransfer = $this->haveMerchantOrderItem([
            MerchantOrderItemTransfer::FK_STATE_MACHINE_ITEM_STATE => $stateMachineItemState->getIdStateMachineItemState(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
        ]);

        $merchantOrderTransfer->addMerchantOrderItem($merchantOrderItemTransfer);

        return $merchantOrderTransfer;
    }
}
