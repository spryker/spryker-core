<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Dependency\Facade;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;

interface MerchantOmsToStateMachineFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     *
     * @return int
     */
    public function triggerForNewStateMachineItem(StateMachineProcessTransfer $stateMachineProcessTransfer, $identifier);

    /**
     * @param string $eventName
     * @param array<\Generated\Shared\Transfer\StateMachineItemTransfer> $stateMachineItems
     *
     * @return int
     */
    public function triggerEventForItems($eventName, array $stateMachineItems);

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessCriteriaTransfer $stateMachineProcessCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer|null
     */
    public function findStateMachineProcess(
        StateMachineProcessCriteriaTransfer $stateMachineProcessCriteriaTransfer
    ): ?StateMachineProcessTransfer;

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return array<string>
     */
    public function getProcessStateNames(StateMachineProcessTransfer $stateMachineProcessTransfer): array;

    /**
     * @param array<\Generated\Shared\Transfer\StateMachineItemTransfer> $stateMachineItems
     *
     * @return array<array<string>>
     */
    public function getManualEventsForStateMachineItems(array $stateMachineItems);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return array<string>
     */
    public function getManualEventsForStateMachineItem(StateMachineItemTransfer $stateMachineItemTransfer);
}
