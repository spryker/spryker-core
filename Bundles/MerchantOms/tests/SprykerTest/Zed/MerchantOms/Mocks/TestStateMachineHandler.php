<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms\Mocks;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;

class TestStateMachineHandler implements StateMachineHandlerInterface
{
    /**
     * @var \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected $stateMachineItemTransfer;

    /**
     * @return array
     */
    public function getCommandPlugins(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getConditionPlugins(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getStateMachineName(): string
    {
        return 'Merchant';
    }

    /**
     * @return string[]
     */
    public function getActiveProcesses(): array
    {
        return ['Test01'];
    }

    /**
     * @param string $processName
     *
     * @return string
     */
    public function getInitialStateForProcess($processName): string
    {
        return 'new';
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function itemStateUpdated(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $this->stateMachineItemTransfer = $stateMachineItemTransfer;

        return true;
    }

    /**
     * @param int[] $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByStateIds(array $stateIds = []): array
    {
        return [$this->stateMachineItemTransfer];
    }

    /**
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function getStateMachineItemTransfer(): StateMachineItemTransfer
    {
        return $this->stateMachineItemTransfer;
    }
}
