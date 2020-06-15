<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Persistence\Expander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class ProductAbstractAvailabilityQueryExpander implements ProductAbstractAvailabilityQueryExpanderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityAbstractQueryCriteriaExpanderPluginInterface[]
     */
    protected $availabilityAbstractQueryCriteriaExpanderPlugins;

    /**
     * @param \Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityAbstractQueryCriteriaExpanderPluginInterface[] $availabilityAbstractQueryCriteriaExpanderPlugins
     */
    public function __construct(array $availabilityAbstractQueryCriteriaExpanderPlugins)
    {
        $this->availabilityAbstractQueryCriteriaExpanderPlugins = $availabilityAbstractQueryCriteriaExpanderPlugins;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
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

        foreach ($this->availabilityAbstractQueryCriteriaExpanderPlugins as $availabilityAbstractQueryCriteriaExpanderPlugin) {
            $queryCriteriaTransfer = $availabilityAbstractQueryCriteriaExpanderPlugin->expandQueryCriteria($queryCriteriaTransfer);
        }

        return $queryCriteriaTransfer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
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
            $query->addJoin($queryJoinTransfer->getLeft(), $queryJoinTransfer->getRight(), $joinType);
        }

        return $query;
    }
}
