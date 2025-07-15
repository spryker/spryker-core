<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory getBusinessFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class SspInquiryStateMachineHandlerPlugin extends AbstractPlugin implements StateMachineHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface>
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
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface>
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
        return $this->getConfig()->getInquiryStateMachineName();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getActiveProcesses(): array
    {
        return array_unique($this->getConfig()->getSspInquiryStateMachineProcessInquiryTypeMap());
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
        return $this->getConfig()->getInquiryInitialStateMachineMap()[$processName];
    }

    /**
     * {@inheritDoc}
     * - Updates ssp inquiry state.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function itemStateUpdated(StateMachineItemTransfer $stateMachineItemTransfer): bool
    {
        /**
         * @var \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry $sspInquiryEntity
         */
         $sspInquiryEntity = SpySspInquiryQuery::create()
            ->filterByIdSspInquiry($stateMachineItemTransfer->getIdentifier())
            ->findOne();

         $sspInquiryEntity->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState())
            ->save();

        return true;
    }

    /**
     * {@inheritDoc}
     * - Finds inquiries with provided state ids.
     * - Returns StateMachineItem transfers with identifier(id of inquiry) and idItemState.
     *
     * @api
     *
     * @param array<int> $stateIds
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function getStateMachineItemsByStateIds(array $stateIds = []): array
    {
        return $this->getRepository()->getStateMachineItemsByStateIds($stateIds);
    }
}
