<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger;

use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\AclEntity\Persistence\Exception\QueryMergerJoinMalfunctionException;

class AclEntityQueryMerger implements AclEntityQueryMergerInterface
{
    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $dstQuery
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $srcQuery
     *
     * @phpstan-return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $dstQuery
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $srcQuery
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\QueryMergerJoinMalfunctionException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mergeQueries(ModelCriteria $dstQuery, ModelCriteria $srcQuery): ModelCriteria
    {
        foreach ($srcQuery->getJoins() as $alias => $srcJoin) {
            if (!$srcJoin->getRightTableName() || !$srcJoin->getRightTableAliasOrName()) {
                throw new QueryMergerJoinMalfunctionException();
            }

            $rightTableName = (string)$srcJoin->getRightTableName();
            if ($dstQuery->getTableMap()->getName() === $rightTableName) {
                continue;
            }

            $foundJoin = $this->searchJoinInJoinCollection($srcJoin, $dstQuery->getJoins());
            if (!$foundJoin) {
                $dstQuery->addJoinObject($srcJoin, $alias);

                continue;
            }

            if ($this->areEqualJoins($foundJoin, $srcJoin)) {
                continue;
            }

            $newAlias = $this->createUniqueAliasBasedOnExisting($dstQuery, (string)$srcJoin->getRightTableAliasOrName());
            $srcJoin->setRightTableAlias($newAlias);
            $dstQuery->addAlias($newAlias, $rightTableName);
            $dstQuery->addJoinObject($srcJoin, $newAlias);
        }

        $dstQuery->putAll($srcQuery->getMap());

        return $dstQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $searchJoin
     * @param \Propel\Runtime\ActiveQuery\Join[] $joins
     *
     * @return \Propel\Runtime\ActiveQuery\Join|null
     */
    protected function searchJoinInJoinCollection(Join $searchJoin, array $joins): ?Join
    {
        foreach ($joins as $srcJoin) {
            if ($srcJoin->getRightTableName() === $searchJoin->getRightTableName()) {
                return $srcJoin;
            }
        }

        return null;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join1
     * @param \Propel\Runtime\ActiveQuery\Join $join2
     *
     * @return bool
     */
    protected function areEqualJoins(Join $join1, Join $join2): bool
    {
        return $join1->getLeftColumns() === $join2->getLeftColumns()
            && $join1->getRightColumns() === $join2->getRightColumns()
            && $join1->getJoinType() === $join2->getJoinType();
    }

    /**
     * @phpstan-param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $dstQuery
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $dstQuery
     * @param string $aliasToExtend
     *
     * @return string
     */
    protected function createUniqueAliasBasedOnExisting(ModelCriteria $dstQuery, string $aliasToExtend): string
    {
        $index = 1;
        while (in_array($aliasToExtend . $index, $dstQuery->getAliases())) {
            $index++;
        }

        return $aliasToExtend . $index;
    }
}
