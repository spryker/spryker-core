<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageQueryContainer extends AbstractQueryContainer implements ProductLabelStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
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
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedByIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryAllProductAbstractLocalizedAttributes()
            ->joinWithLocale()
            ->joinWithSpyProductAbstract()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstractByProductAbstractIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductLabelQuery()
            ->queryAllProductLabelProductAbstractRelations()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithSpyProductLabel()
            ->orderBy(SpyProductLabelTableMap::COL_POSITION)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * @api
     *
     * @param array $productLabelProductAbstractIds
     *
     * @return \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstractByIds(array $productLabelProductAbstractIds)
    {
        return $this->getFactory()
            ->getProductLabelQuery()
            ->queryAllProductLabelProductAbstractRelations()
            ->filterByIdProductLabelProductAbstract_In($productLabelProductAbstractIds)
            ->joinWithSpyProductLabel()
            ->orderBy(SpyProductLabelTableMap::COL_POSITION)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * @api
     *
     * @return \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelProductAbstract()
    {
        return $this->getFactory()
            ->getProductLabelQuery()
            ->queryAllProductLabelProductAbstractRelations();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryProductLabelLocalizedAttributes()
    {
        return $this->getFactory()
            ->getProductLabelQuery()
            ->queryAllLocalizedAttributesLabels()
            ->joinWithSpyLocale()
            ->joinWithSpyProductLabel()
            ->addAnd(SpyProductLabelTableMap::COL_IS_ACTIVE, true, Criteria::EQUAL);
    }
}
