<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Checker;

use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;

class FlagChecker implements FlagCheckerInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    protected $builder;

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     */
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string $flag
     *
     * @return bool
     */
    public function hasOrderItemsFlag(array $itemTransfers, string $flag): bool
    {
        $processName = end($itemTransfers)->getProcess();

        if (!$processName) {
            return false;
        }

        $filteredItemTransfers = $this->filterOrderItemsByStateWithFlag(
            $itemTransfers,
            $this->getStatesByFlag($processName, $flag)
        );

        return count($itemTransfers) === count($filteredItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function filterOrderItemsByStateWithFlag(array $itemTransfers, array $states): array
    {
        $filteredItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getState() || !$itemTransfer->getState()->getName()) {
                continue;
            }

            if (!array_key_exists($itemTransfer->getState()->getName(), $states)) {
                continue;
            }

            $filteredItemTransfers[] = $itemTransfer;
        }

        return $filteredItemTransfers;
    }

    /**
     * @param string $processName
     * @param string $flag
     *
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface[]
     */
    protected function getStatesByFlag(string $processName, string $flag): array
    {
        $states = [];
        $processStateList = (clone $this->builder)->createProcess($processName)->getAllStates();

        foreach ($processStateList as $state) {
            if ($state->hasFlag($flag)) {
                $states[$state->getName()] = $state;
            }
        }

        return $states;
    }
}
