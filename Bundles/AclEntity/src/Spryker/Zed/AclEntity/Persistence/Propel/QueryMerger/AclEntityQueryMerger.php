<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\QueryMerger;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Zed\AclEntity\Persistence\Exception\QueryMergerJoinMalfunctionException;
use Spryker\Zed\AclEntity\Persistence\Propel\Generator\AclEntityAliasGeneratorInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Matcher\JoinMatcherInterface;

class AclEntityQueryMerger implements AclEntityQueryMergerInterface
{
    /**
     * @var string
     */
    protected const JOIN_CONDITION_TEMPLATE = '%s.%s %s %s.%s';

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Matcher\JoinMatcherInterface
     */
    protected JoinMatcherInterface $joinMatcher;

    /**
     * @var \Spryker\Zed\AclEntity\Persistence\Propel\Generator\AclEntityAliasGeneratorInterface
     */
    protected $queryAliasGenerator;

    /**
     * @var \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    protected $aclEntityService;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Matcher\JoinMatcherInterface $joinMatcher
     * @param \Spryker\Zed\AclEntity\Persistence\Propel\Generator\AclEntityAliasGeneratorInterface $queryAliasGenerator
     * @param \Spryker\Service\AclEntity\AclEntityServiceInterface $aclEntityService
     */
    public function __construct(
        JoinMatcherInterface $joinMatcher,
        AclEntityAliasGeneratorInterface $queryAliasGenerator,
        AclEntityServiceInterface $aclEntityService
    ) {
        $this->joinMatcher = $joinMatcher;
        $this->queryAliasGenerator = $queryAliasGenerator;
        $this->aclEntityService = $aclEntityService;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $dstQuery
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface> $srcQuery
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\QueryMergerJoinMalfunctionException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<\Propel\Runtime\ActiveRecord\ActiveRecordInterface>
     */
    public function mergeQueries(ModelCriteria $dstQuery, ModelCriteria $srcQuery): ModelCriteria
    {
        foreach ($srcQuery->getJoins() as $alias => $srcJoin) {
            if (!$srcJoin->getRightTableName() || !$srcJoin->getRightTableAliasOrName()) {
                throw new QueryMergerJoinMalfunctionException();
            }
            if ($this->isSegmentTableJoin($srcJoin) && in_array($srcJoin->getLeftTableName(), $dstQuery->getAliases())) {
                /** @var string $leftTableAlias */
                $leftTableAlias = array_search($srcJoin->getLeftTableName(), $dstQuery->getAliases());
                $srcJoin->setLeftTableAlias($leftTableAlias);
                $srcJoin = $this->rebuildJoinCondition($srcJoin);
            }

            $rightTableName = (string)$srcJoin->getRightTableName();
            if ($dstQuery->getTableMapOrFail()->getName() === $rightTableName) {
                continue;
            }

            if (!$this->joinMatcher->matchByRightTableName((string)$srcJoin->getRightTableName(), $dstQuery->getJoins())) {
                $dstQuery->addJoinObject($srcJoin, $alias);

                continue;
            }

            if ($this->joinMatcher->matchByCompleteEquality($srcJoin, $dstQuery->getJoins())) {
                continue;
            }

            $aclEntityTableAlias = $this->queryAliasGenerator->generateTableAlias(
                $dstQuery,
                (string)$srcJoin->getRightTableAliasOrName(),
            );
            $srcJoin->setRightTableAlias($aclEntityTableAlias);
            /** @var \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion|null $srcJoinCondition */
            $srcJoinCondition = $srcJoin->getJoinCondition();
            $srcJoin = $srcJoinCondition ? $this->rebuildJoinCondition($srcJoin) : $srcJoin;

            $dstQuery->addAlias($aclEntityTableAlias, $rightTableName);
            $dstQuery->addJoinObject($srcJoin, $aclEntityTableAlias);
        }

        $dstQuery->putAll($srcQuery->getMap());

        return $dstQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @throws \Spryker\Zed\AclEntity\Persistence\Exception\QueryMergerJoinMalfunctionException
     *
     * @return bool
     */
    protected function isSegmentTableJoin(Join $join): bool
    {
        $leftTableName = $join->getLeftTableName();
        $rightTableName = $join->getRightTableName();
        if (!$leftTableName || !$rightTableName) {
            throw new QueryMergerJoinMalfunctionException();
        }

        return $this->aclEntityService->generateSegmentConnectorTableName($leftTableName) === $rightTableName;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Join $join
     *
     * @return \Propel\Runtime\ActiveQuery\Join
     */
    protected function rebuildJoinCondition(Join $join): Join
    {
        $clauses = $join->getJoinConditionOrFail()->getClauses();
        $join->buildJoinCondition(new Criteria());
        foreach ($clauses as $clause) {
            $join->getJoinConditionOrFail()->addAnd($clause);
        }

        return $join;
    }
}
