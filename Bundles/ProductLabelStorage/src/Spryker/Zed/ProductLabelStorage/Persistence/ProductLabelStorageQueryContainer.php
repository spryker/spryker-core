<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageQueryContainer extends AbstractQueryContainer implements ProductLabelStorageQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery
     */
    public function queryProductAbstractLabelStorageByIds(array $productAbstractIds)
    {
        return $this
            ->getFactory()
            ->createSpyProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery
     */
    public function queryProductLabelDictionaryStorage()
    {
        return $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstractByProductAbstractIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryAllProductLabelProductAbstractRelations()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithSpyProductLabel()
            ->orderBy(SpyProductLabelTableMap::COL_POSITION)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productLabelProductAbstractIds
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstractByPrimaryIds(array $productLabelProductAbstractIds): SpyProductLabelProductAbstractQuery
    {
        return $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryAllProductLabelProductAbstractRelations()
            ->filterByIdProductLabelProductAbstract_In($productLabelProductAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstract()
    {
        return $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryAllProductLabelProductAbstractRelations();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryProductLabelLocalizedAttributes()
    {
        return $this->getFactory()
            ->getProductLabelQueryContainer()
            ->queryAllLocalizedAttributesLabels()
            ->joinWithSpyLocale()
            ->joinWithSpyProductLabel()
            ->addAnd(SpyProductLabelTableMap::COL_IS_ACTIVE, true, Criteria::EQUAL);
    }
}
