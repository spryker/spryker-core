<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductNew\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductNew\Persistence\ProductNewPersistenceFactory getFactory()
 */
class ProductNewQueryContainer extends AbstractQueryContainer implements ProductNewQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $labelName
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelByName($labelName)
    {
        return $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryProductLabelByName($labelName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryRelationsBecomingInactive($idProductLabel)
    {
        $timeRangeCriteria = $this->createValidTimeRangeCriteria(true);

        return $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryProductAbstractRelationsByIdProductLabel($idProductLabel)
            ->useSpyProductAbstractQuery()
                ->mergeWith($timeRangeCriteria)
                ->_or()
                ->where(sprintf(
                    '%s IS NULL AND %s IS NULL',
                    SpyProductAbstractTableMap::COL_NEW_FROM,
                    SpyProductAbstractTableMap::COL_NEW_TO
                ))
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryRelationsBecomingActive($idProductLabel)
    {
        $timeRangeCriteria = $this->createValidTimeRangeCriteria(false);

        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
                ->mergeWith($timeRangeCriteria)
            ->useSpyProductLabelProductAbstractQuery('rel', Criteria::LEFT_JOIN)
                ->filterByFkProductLabel(null, Criteria::ISNULL)
            ->endUse()
            ->addJoinCondition('rel', sprintf('rel.fk_product_label = %d', $idProductLabel));
    }

    /**
     * @param bool $isNegative
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function createValidTimeRangeCriteria($isNegative)
    {
        $clause = sprintf(
            '%3$s (
                (%1$s <= ? OR (
                    %1$s IS NULL AND
                    %2$s IS NOT NULL
                )) AND
                (%2$s >= ? OR (
                    %2$s IS NULL AND
                    %1$s IS NOT NULL
                ))
            )',
            SpyProductAbstractTableMap::COL_NEW_FROM,
            SpyProductAbstractTableMap::COL_NEW_TO,
            $isNegative ? 'NOT' : ''
        );

        $criteria = new ModelCriteria(null, SpyProductAbstract::class);
        $criteria->where($clause, ['now', 'now']);

        return $criteria;
    }
}
