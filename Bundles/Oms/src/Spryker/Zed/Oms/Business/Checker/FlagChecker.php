<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Checker;

use Generated\Shared\Transfer\OrderTransfer;
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $flag
     *
     * @return bool
     */
    public function hasOrderItemsFlag(OrderTransfer $orderTransfer, string $flag): bool
    {
        $processName = $this->extractProcessName($orderTransfer);

        if (!$processName) {
            return false;
        }

        $filteredItemTransfers = $this->filterOrderItemsByStateWithFlag(
            $orderTransfer,
            $this->getStatesByFlag($processName, $flag)
        );

        return $orderTransfer->getItems()->count() === count($filteredItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<string, \Spryker\Zed\Oms\Business\Process\StateInterface> $states
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function filterOrderItemsByStateWithFlag(OrderTransfer $orderTransfer, array $states): array
    {
        $filteredItemTransfers = [];

        foreach ($orderTransfer->getItems() as $item) {
            if (!$item->getState() || !$item->getState()->getName()) {
                continue;
            }

            if (!array_key_exists($item->getState()->getName(), $states)) {
                continue;
            }

            $filteredItemTransfers[] = $item;
        }

        return $filteredItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string|null
     */
    protected function extractProcessName(OrderTransfer $orderTransfer): ?string
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        return $itemTransfer->getProcess();
    }

    /**
     * @param string $processName
     * @param string $flag
     *
     * @return array<string, \Spryker\Zed\Oms\Business\Process\StateInterface>
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
