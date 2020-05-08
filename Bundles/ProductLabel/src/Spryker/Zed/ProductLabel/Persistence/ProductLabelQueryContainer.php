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
    public const COL_MAX_POSITION = 'max_position';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepository::getAllProductLabelsSortedByPosition()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepository::findProductLabelById} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepository::findProductLabelByName} instead.
     *
     * @param string $labelName
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelByName($labelName)
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->filterByName($labelName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @depreacted Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepository::getProductLabelsByIdProductAbstract} instead.
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductsLabelByIdProductAbstract($idProductAbstract)
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->useSpyProductLabelProductAbstractQuery()
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @depreacted Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepository::getActiveProductLabelIdsByIdProductAbstract} instead.
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryActiveProductsLabelByIdProductAbstract($idProductAbstract)
    {
        return $this->queryProductsLabelByIdProductAbstract($idProductAbstract)
            ->filterByIsActive(true);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryLocalizedAttributesByIdProductLabel($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createLocalizedAttributesQuery()
            ->filterByFkProductLabel($idProductLabel);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryAllLocalizedAttributesLabels()
    {
        return $this->getFactory()
            ->createLocalizedAttributesQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idProductLabel
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryLocalizedAttributesByIdProductLabelAndIdLocale($idProductLabel, $idLocale)
    {
        return $this
            ->queryLocalizedAttributesByIdProductLabel($idProductLabel)
            ->filterByFkLocale($idLocale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductAbstractRelationsByIdProductLabel($idProductLabel)
    {
        return $this
            ->getFactory()
            ->createProductRelationQuery()
            ->filterByFkProductLabel($idProductLabel);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryAllProductLabelProductAbstractRelations()
    {
        return $this
            ->getFactory()
            ->createProductRelationQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductAbstractRelationsByIdProductLabelAndIdsProductAbstract(
        $idProductLabel,
        array $idsProductAbstract
    ) {
        return $this
            ->queryProductAbstractRelationsByIdProductLabel($idProductLabel)
            ->filterByFkProductAbstract($idsProductAbstract, Criteria::IN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryUnpublishedProductLabelsBecomingValid()
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->filterByIsPublished(false)
            ->_or()
            ->filterByIsPublished(null, Criteria::ISNULL)
            ->filterByValidFrom('now', Criteria::LESS_EQUAL)
            ->filterByValidTo(null, Criteria::ISNULL)
            ->_or()
            ->filterByValidTo('now', Criteria::GREATER_EQUAL);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryPublishedProductLabelsBecomingInvalid()
    {
        return $this
            ->getFactory()
            ->createProductLabelQuery()
            ->filterByIsPublished(true)
            ->_or()
            ->filterByIsPublished(null, Criteria::ISNULL)
            ->filterByValidTo('now', Criteria::LESS_THAN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryValidProductLabelsByIdProductAbstract($idProductAbstract)
    {
        return $this->queryProductsLabelByIdProductAbstract($idProductAbstract)
            ->filterByIsActive(true)
            ->filterByValidFrom('now', Criteria::LESS_EQUAL)
            ->_or()
            ->filterByValidFrom(null, Criteria::ISNULL)
            ->filterByValidTo('now', Criteria::GREATER_EQUAL)
            ->_or()
            ->filterByValidTo(null, Criteria::ISNULL)
            ->orderByIsExclusive(Criteria::DESC)
            ->orderByPosition(Criteria::ASC);
    }
}
