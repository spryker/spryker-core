<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer;
use RuntimeException;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

trait OrderStateOrderItemsFetchTrait
{
    /**
     * @param array<\Spryker\Zed\Oms\Business\Process\TransitionInterface> $transitions
     *
     * @return array
     */
    protected function createStateToTransitionMap(array $transitions)
    {
        $stateToTransitionsMap = [];
        foreach ($transitions as $transition) {
            $sourceId = $transition->getSource()->getName();
            if (array_key_exists($sourceId, $stateToTransitionsMap) === false) {
                $stateToTransitionsMap[$sourceId] = [];
            }
            $stateToTransitionsMap[$sourceId][] = $transition;
        }

        return $stateToTransitionsMap;
    }

    /**
     * @param array $states
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer|null $omsCheckConditionsQueryCriteriaTransfer
     *
     * @throws \RuntimeException
     *
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    protected function getOrderItemsByState(
        array $states,
        ProcessInterface $process,
        ?OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer
    ) {
        if (!($this->queryContainer instanceof OmsQueryContainerInterface)) {
            throw new RuntimeException('Query container is not set or does not implement OmsQueryContainerInterface.');
        }
        $omsCheckConditionsQueryCriteriaTransfer = $this->prepareOmsCheckConditionsQueryCriteriaTransfer($omsCheckConditionsQueryCriteriaTransfer);

        $storeName = $omsCheckConditionsQueryCriteriaTransfer->getStoreName();
        $limit = $omsCheckConditionsQueryCriteriaTransfer->getLimit();

        if ($storeName === null && $limit === null) {
            return $this->queryContainer
                ->querySalesOrderItemsByState($states, $process->getName())
                ->find()
                ->getData();
        }

        $omsProcessEntity = $this->queryContainer->queryProcess($process->getName())->findOne();
        /** @var \Propel\Runtime\Collection\ObjectCollection $omsOrderItemEntityCollection */
        $omsOrderItemEntityCollection = $this->queryContainer->querySalesOrderItemStatesByName($states)->find();

        if ($omsProcessEntity === null || $omsOrderItemEntityCollection->count() === 0) {
            return [];
        }

        return $this->queryContainer
            ->querySalesOrderItemsByProcessIdStateIdsAndQueryCriteria(
                $omsProcessEntity->getIdOmsOrderProcess(),
                $omsOrderItemEntityCollection->getPrimaryKeys(),
                $omsCheckConditionsQueryCriteriaTransfer,
            )
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer|null $omsCheckConditionsQueryCriteriaTransfer
     *
     * @throws \RuntimeException
     *
     * @return \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer
     */
    protected function prepareOmsCheckConditionsQueryCriteriaTransfer(
        ?OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer = null
    ): OmsCheckConditionsQueryCriteriaTransfer {
        if (!($this->omsConfig instanceof OmsConfig)) {
            throw new RuntimeException('OmsConfig is not set or does not implement OmsConfig.');
        }
        if ($omsCheckConditionsQueryCriteriaTransfer === null) {
            $omsCheckConditionsQueryCriteriaTransfer = new OmsCheckConditionsQueryCriteriaTransfer();
        }

        if ($omsCheckConditionsQueryCriteriaTransfer->getLimit() === null) {
            $omsCheckConditionsQueryCriteriaTransfer->setLimit($this->omsConfig->getCheckConditionsQueryLimit());
        }

        return $omsCheckConditionsQueryCriteriaTransfer;
    }
}
