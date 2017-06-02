<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelPersistenceFactory getFactory()
 */
class ProductLabelQueryContainer extends AbstractQueryContainer implements ProductLabelQueryContainerInterface
{

    const COL_MAX_POSITION = 'max_position';

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelsSortedByPosition()
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->orderByPosition(Criteria::ASC);
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelById($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->filterByIdProductLabel($idProductLabel);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelByAbstractProduct($idProductAbstract)
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->useSpyProductLabelProductAbstractQuery()
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryLocalizedAttributesByProductLabel($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createLocalizedAttributesQuery()
            ->filterByFkProductLabel($idProductLabel);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryMaxPosition()
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->withColumn(
                sprintf('MAX(%s)', SpyProductLabelTableMap::COL_POSITION),
                static::COL_MAX_POSITION
            )
            ->select([
                static::COL_MAX_POSITION,
            ]);
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryAbstractProductRelationsByProductLabel($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createProductRelationQuery()
            ->filterByFkProductLabel($idProductLabel);
    }

    /**
     * @api
     *
     * @param int $idProductLabel
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryAbstractProductRelationsByProductLabelAndAbstractProduct($idProductLabel, $idProductAbstract)
    {
        return $this
            ->queryAbstractProductRelationsByProductLabel($idProductLabel)
            ->filterByFkProductAbstract($idProductAbstract);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryUnpublishedProductLabelBecomingValid()
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->filterByIsPublished(false)
            ->filterByValidFrom('now', Criteria::LESS_EQUAL)
            ->filterByValidTo('now', Criteria::GREATER_EQUAL);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryPublishedProductLabelBecomingInvalid()
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->filterByIsPublished(true)
            ->filterByValidTo('now', Criteria::LESS_THAN);
    }

}
