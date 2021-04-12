<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication\Plugin\StateMachine;

use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantOms\MerchantOmsConfig;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;

/**
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOms\Communication\MerchantOmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 */
class MerchantStateMachineHandlerPlugin extends AbstractPlugin implements StateMachineHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface[]
     */
    public function getCommandPlugins(): array
    {
        return $this->getFactory()->getStateMachineCommandPlugins();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface[]
     */
    public function getConditionPlugins(): array
    {
        return $this->getFactory()->getStateMachineConditionPlugins();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getStateMachineName(): string
    {
        return MerchantOmsConfig::MERCHANT_OMS_STATE_MACHINE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getActiveProcesses(): array
    {
        return $this->getConfig()->getMerchantOmsProcesses();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $processName
     *
     * @return string
     */
    public function getInitialStateForProcess($processName): string
    {
        return $this->getConfig()->getMerchantProcessInitialStateMap()[$processName];
    }

    /**
     * {@inheritDoc}
     * - Updates merchant order item state.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function itemStateUpdated(StateMachineItemTransfer $stateMachineItemTransfer): bool
    {
        $merchantOrderItemResponseTransfer = $this->getFactory()->getMerchantSalesOrderFacade()
            ->updateMerchantOrderItem(
                (new MerchantOrderItemTransfer())
                    ->setIdMerchantOrderItem($stateMachineItemTransfer->getIdentifier())
                    ->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState())
            );

        /** @var bool $isSuccessfulMerchantOrderItemResponse */
        $isSuccessfulMerchantOrderItemResponse = $merchantOrderItemResponseTransfer->getIsSuccessful();

        return $isSuccessfulMerchantOrderItemResponse;
    }

    /**
     * {@inheritDoc}
     * - Finds merchant order items with provided state ids.
     * - Returns StateMachineItem transfers with identifier(id of merchant order item) and idItemState.
     *
     * @api
     *
     * @param int[] $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByStateIds(array $stateIds = []): array
    {
        return $this->getFacade()->getStateMachineItemsByStateIds($stateIds);
    }
}
