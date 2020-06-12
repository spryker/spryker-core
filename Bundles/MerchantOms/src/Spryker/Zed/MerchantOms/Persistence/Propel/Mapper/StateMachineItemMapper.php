<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState;

class StateMachineItemMapper
{
    /**
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState $stateMachineItemState
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function mapStateMachineItemEntityToStateMachineItemTransfer(
        SpyStateMachineItemState $stateMachineItemState,
        StateMachineItemTransfer $stateMachineItemTransfer
    ): StateMachineItemTransfer {
        return $stateMachineItemTransfer
            ->fromArray($stateMachineItemState->toArray(), true)
            ->setStateName($stateMachineItemState->getName());
    }
}
