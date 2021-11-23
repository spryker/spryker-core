<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Comparator;

use Propel\Runtime\ActiveQuery\Join;

class JoinComparator implements JoinComparatorInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join1
     * @param \Propel\Runtime\ActiveQuery\Join $join2
     *
     * @return bool
     */
    public function areEqual(Join $join1, Join $join2): bool
    {
        return $this->areSameJoinTypes($join1, $join2)
            && $this->areSameLeftTables($join1, $join2)
            && $this->areSameRightTables($join1, $join2)
            && $this->areSameLeftColumns($join1, $join2)
            && $this->areSameRightColumns($join1, $join2)
            && $this->areSameConditions($join1, $join2);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join1
     * @param \Propel\Runtime\ActiveQuery\Join $join2
     *
     * @return bool
     */
    protected function areSameJoinTypes(Join $join1, Join $join2): bool
    {
        if ($join1->getJoinType() === $join2->getJoinType()) {
            return true;
        }

        return $this->isInnerJoin($join1) && $this->isInnerJoin($join2);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join1
     * @param \Propel\Runtime\ActiveQuery\Join $join2
     *
     * @return bool
     */
    protected function areSameLeftTables(Join $join1, Join $join2): bool
    {
        return $join1->getLeftTableName() === $join2->getLeftTableName();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join1
     * @param \Propel\Runtime\ActiveQuery\Join $join2
     *
     * @return bool
     */
    protected function areSameRightTables(Join $join1, Join $join2): bool
    {
        return $join1->getRightTableName() === $join2->getRightTableName();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join1
     * @param \Propel\Runtime\ActiveQuery\Join $join2
     *
     * @return bool
     */
    protected function areSameLeftColumns(Join $join1, Join $join2): bool
    {
        if (count($join1->getLeftColumns()) !== count($join2->getLeftColumns())) {
            return false;
        }
        foreach (array_keys($join1->getLeftColumns()) as $i) {
            if ($join1->getLeftColumnName($i) !== $join2->getLeftColumnName($i)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join1
     * @param \Propel\Runtime\ActiveQuery\Join $join2
     *
     * @return bool
     */
    protected function areSameRightColumns(Join $join1, Join $join2): bool
    {
        if (count($join1->getRightColumns()) !== count($join2->getRightColumns())) {
            return false;
        }
        foreach (array_keys($join1->getRightColumns()) as $i) {
            if ($join1->getRightColumnName($i) !== $join2->getRightColumnName($i)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return bool
     */
    protected function isInnerJoin(Join $join): bool
    {
        return in_array($join->getJoinType(), [null, Join::INNER_JOIN]);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join1
     * @param \Propel\Runtime\ActiveQuery\Join $join2
     *
     * @return bool
     */
    protected function areSameConditions(Join $join1, Join $join2): bool
    {
        if (count($join1->getConditions()) !== count($join2->getConditions())) {
            return false;
        }

        foreach (array_keys($join1->getConditions()) as $i) {
            if ($join1->getOperator($i) !== $join2->getOperator($i)) {
                return false;
            }
        }

        return true;
    }
}
