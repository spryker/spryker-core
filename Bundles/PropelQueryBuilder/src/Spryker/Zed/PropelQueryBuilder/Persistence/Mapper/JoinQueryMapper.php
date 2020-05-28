<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\Mapper;

use ArrayObject;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class JoinQueryMapper
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \ArrayObject|\Generated\Shared\Transfer\PropelQueryBuilderJoinTransfer[] $propelQueryBuilderJoinTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapJoin(ModelCriteria $query, ArrayObject $propelQueryBuilderJoinTransfers): ModelCriteria
    {
        foreach ($propelQueryBuilderJoinTransfers as $propelQueryBuilderJoinTransfer) {
            if ($propelQueryBuilderJoinTransfer->getRelation()) {
                $query->join($propelQueryBuilderJoinTransfer->getRelation(), $propelQueryBuilderJoinTransfer->getJoinType());

                if ($propelQueryBuilderJoinTransfer->getCondition()) {
                    $query->addJoinCondition($propelQueryBuilderJoinTransfer->getRelation(), $propelQueryBuilderJoinTransfer->getCondition());
                }

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
}
