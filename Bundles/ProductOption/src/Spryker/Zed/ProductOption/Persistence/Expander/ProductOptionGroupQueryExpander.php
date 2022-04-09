<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence\Expander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class ProductOptionGroupQueryExpander implements ProductOptionGroupQueryExpanderInterface
{
    /**
     * @var array<\Spryker\Zed\ProductOptionGuiExtension\Dependency\Plugin\ProductOptionListTableQueryCriteriaExpanderPluginInterface>
     */
    protected $productOptionListTableQueryCriteriaExpanderPlugins;

    /**
     * @param array<\Spryker\Zed\ProductOptionGuiExtension\Dependency\Plugin\ProductOptionListTableQueryCriteriaExpanderPluginInterface> $productOptionListTableQueryCriteriaExpanderPlugins
     */
    public function __construct(array $productOptionListTableQueryCriteriaExpanderPlugins)
    {
        $this->productOptionListTableQueryCriteriaExpanderPlugins = $productOptionListTableQueryCriteriaExpanderPlugins;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<mixed> $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<mixed>
     */
    public function expandQuery(ModelCriteria $query): ModelCriteria
    {
        $this->addJoin($query, $this->buildQueryCriteriaTransfer());

        return $query;
    }

    /**
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    protected function buildQueryCriteriaTransfer(): QueryCriteriaTransfer
    {
        $queryCriteriaTransfer = new QueryCriteriaTransfer();

        foreach ($this->productOptionListTableQueryCriteriaExpanderPlugins as $productOptionListTableQueryCriteriaExpanderPlugin) {
            $queryCriteriaTransfer = $productOptionListTableQueryCriteriaExpanderPlugin->expandQueryCriteria($queryCriteriaTransfer);
        }

        return $queryCriteriaTransfer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria<mixed> $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<mixed>
     */
    protected function addJoin(
        ModelCriteria $query,
        QueryCriteriaTransfer $queryCriteriaTransfer
    ): ModelCriteria {
        foreach ($queryCriteriaTransfer->getJoins() as $queryJoinTransfer) {
            $joinType = $queryJoinTransfer->getJoinType() ?? Criteria::INNER_JOIN;
            if ($queryJoinTransfer->getRelation()) {
                $query->join($queryJoinTransfer->getRelation(), $joinType);

                if ($queryJoinTransfer->getCondition()) {
                    $query->addJoinCondition($queryJoinTransfer->getRelation(), $queryJoinTransfer->getCondition());
                }

                continue;
            }

            if ($queryJoinTransfer->getWhereConditions()->count()) {
                foreach ($queryJoinTransfer->getWhereConditions() as $whereConditionTransfer) {
                    /** @var literal-string $where */
                    $where = sprintf(
                        '%s=%d',
                        $whereConditionTransfer->getColumn(),
                        $whereConditionTransfer->getValue(),
                    );
                    $query->where(
                        $where,
                    );
                }
            }

            $query->addJoin($queryJoinTransfer->getLeft(), $queryJoinTransfer->getRight(), $joinType);
        }

        return $query;
    }
}
