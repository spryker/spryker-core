<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms;

use Codeception\Actor;
use Generated\Shared\DataBuilder\StateMachineItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess;

/**
 * Inherited Methods
 *
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
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantOmsCommunicationTester extends Actor
{
    use _generated\MerchantOmsCommunicationTesterActions;

   /**
    * Define custom actions here
    */

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
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess $stateMachineProcessEntity
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState
     */
    public function createStateMachineItemState(SpyStateMachineProcess $stateMachineProcessEntity): SpyStateMachineItemState
    {
        $stateMachineItemStateTransfer = (new StateMachineItemBuilder())->build();

        $stateMachineItemStateEntity = $this->createStateMachineItemStatePropelEntity();
        $stateMachineItemStateEntity->setName($stateMachineItemStateTransfer->getEventName());
        $stateMachineItemStateEntity->setFkStateMachineProcess($stateMachineProcessEntity->getIdStateMachineProcess());

        $stateMachineItemStateEntity->save();

        return $stateMachineItemStateEntity;
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState
     */
    protected function createStateMachineItemStatePropelEntity(): SpyStateMachineItemState
    {
        return new SpyStateMachineItemState();
    }
}
