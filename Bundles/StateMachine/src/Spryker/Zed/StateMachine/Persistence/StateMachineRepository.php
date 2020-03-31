<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Persistence;

use Generated\Shared\Transfer\StateMachineProcessCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachinePersistenceFactory getFactory()
 */
class StateMachineRepository extends AbstractRepository implements StateMachineRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessCriteriaTransfer $stateMachineProcessCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer|null
     */
    public function findStateMachineProcess(StateMachineProcessCriteriaTransfer $stateMachineProcessCriteriaFilterTransfer): ?StateMachineProcessTransfer
    {
        $stateMachineProcessQuery = $this->getFactory()->createStateMachineProcessQuery();
        $stateMachineProcessQuery = $this->applyStateMachineProcessFilters($stateMachineProcessQuery, $stateMachineProcessCriteriaFilterTransfer);

        $stateMachineProcessEntity = $stateMachineProcessQuery->findOne();

        if (!$stateMachineProcessEntity) {
            return null;
        }

        return $this->getFactory()
            ->createStateMachineMapper()
            ->mapStateMachineProcessEntityToStateMachineProcessTransfer(
                $stateMachineProcessEntity,
                new StateMachineProcessTransfer()
            );
    }

    /**
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery $stateMachineProcessQuery
     * @param \Generated\Shared\Transfer\StateMachineProcessCriteriaTransfer $stateMachineProcessCriteriaFilterTransfer
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery
     */
    protected function applyStateMachineProcessFilters(
        SpyStateMachineProcessQuery $stateMachineProcessQuery,
        StateMachineProcessCriteriaTransfer $stateMachineProcessCriteriaFilterTransfer
    ): SpyStateMachineProcessQuery {
        if ($stateMachineProcessCriteriaFilterTransfer->getIdStateMachineProcess() !== null) {
            $stateMachineProcessQuery->filterByIdStateMachineProcess($stateMachineProcessCriteriaFilterTransfer->getIdStateMachineProcess());
        }

        return $stateMachineProcessQuery;
    }
}
