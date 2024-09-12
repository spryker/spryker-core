<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Matcher;

use Propel\Runtime\ActiveQuery\Join;
use Spryker\Zed\AclEntity\Persistence\Propel\Comparator\JoinComparatorInterface;

class JoinMatcher implements JoinMatcherInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Comparator\JoinComparatorInterface
     */
    protected JoinComparatorInterface $comparator;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Comparator\JoinComparatorInterface $comparator
     */
    public function __construct(JoinComparatorInterface $comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * @param string $tableName
     * @param array<\Propel\Runtime\ActiveQuery\Join> $joins
     *
     * @return array<\Propel\Runtime\ActiveQuery\Join>
     */
    public function matchByRightTableName(string $tableName, array $joins): array
    {
        $matchedJoins = [];

        foreach ($joins as $join) {
            if ($join->getRightTableName() !== $tableName) {
                continue;
            }

            $matchedJoins[] = $join;
        }

        return $matchedJoins;
    }

    /**
     * @param string $tableName
     * @param array<\Propel\Runtime\ActiveQuery\Join> $joins
     *
     * @return \Propel\Runtime\ActiveQuery\Join|null
     */
    public function matchOneByRightTableName(string $tableName, array $joins): ?Join
    {
        foreach ($joins as $join) {
            if ($join->getRightTableName() === $tableName) {
                return $join;
            }
        }

        return null;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $searchJoin
     * @param array<\Propel\Runtime\ActiveQuery\Join> $joins
     *
     * @return \Propel\Runtime\ActiveQuery\Join|null
     */
    public function matchByCompleteEquality(Join $searchJoin, array $joins): ?Join
    {
        foreach ($joins as $join) {
            if ($this->comparator->areEqual($searchJoin, $join)) {
                return $join;
            }
        }

        return null;
    }
}
