<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\Mapper;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderJoinTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class JoinQueryMapper
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapJoins(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer): ModelCriteria
    {
        foreach ($propelQueryBuilderCriteriaTransfer->getJoins() as $propelQueryBuilderJoinTransfer) {
            if ($propelQueryBuilderJoinTransfer->getRelation()) {
                $query = $this->mapJoinWithRelationToQuery($propelQueryBuilderJoinTransfer, $query);

                continue;
            }

            $query->addJoin(
                $propelQueryBuilderJoinTransfer->getLeft(),
                $propelQueryBuilderJoinTransfer->getRight(),
                $propelQueryBuilderJoinTransfer->getJoinType()
            );
        }

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderJoinTransfer $propelQueryBuilderJoinTransfer
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mapJoinWithRelationToQuery(PropelQueryBuilderJoinTransfer $propelQueryBuilderJoinTransfer, ModelCriteria $query): ModelCriteria
    {
        $query->join($propelQueryBuilderJoinTransfer->getRelation(), $propelQueryBuilderJoinTransfer->getJoinType());

        if ($propelQueryBuilderJoinTransfer->getCondition()) {
            $query->addJoinCondition($propelQueryBuilderJoinTransfer->getRelation(), $propelQueryBuilderJoinTransfer->getCondition());
        }

        return $query;
    }
}
