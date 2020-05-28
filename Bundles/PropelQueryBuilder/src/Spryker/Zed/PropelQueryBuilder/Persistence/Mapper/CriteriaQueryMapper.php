<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\Mapper;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderJoinTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;

class CriteriaQueryMapper
{
    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    public function mapQueryCriteriaTransferToPropelQueryBuilderCriteriaTransfer(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer
    ): PropelQueryBuilderCriteriaTransfer {
        if (!$propelQueryBuilderCriteriaTransfer->getRuleSet()) {
            $propelQueryBuilderCriteriaTransfer->setRuleSet(new PropelQueryBuilderRuleSetTransfer());
        }

        foreach ($queryCriteriaTransfer->getJoins() as $joinTransfer) {
            $propelQueryBuilderCriteriaTransfer->addJoin(
                $this->mapQueryJoinTransferToPropelQueryBuilderJoinTransfer($joinTransfer, new PropelQueryBuilderJoinTransfer())
            );
        }

        return $propelQueryBuilderCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QueryJoinTransfer $queryJoinTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderJoinTransfer $propelQueryBuilderJoinTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderJoinTransfer
     */
    protected function mapQueryJoinTransferToPropelQueryBuilderJoinTransfer(
        QueryJoinTransfer $queryJoinTransfer,
        PropelQueryBuilderJoinTransfer $propelQueryBuilderJoinTransfer
    ): PropelQueryBuilderJoinTransfer {
        return $propelQueryBuilderJoinTransfer->fromArray($queryJoinTransfer->toArray(), true);
    }
}
