<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Persistence;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
use Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStoragePersistenceFactory getFactory()
 */
class ProductOptionStorageQueryContainer extends AbstractQueryContainer implements ProductOptionStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorageQuery
     */
    public function queryProductAbstractOptionStorageByIds(array $productAbstractIds)
    {
        return $this
            ->getFactory()
            ->createSpyProductAbstractStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);
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
            ->joinWithSpyProductAbstract()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryProductOptionsByProductAbstractIds(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductOptionQuery()
            ->queryAllProductAbstractProductOptionGroups()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithSpyProductOptionGroup()
            ->joinWith('SpyProductOptionGroup.SpyProductOptionValue')
            ->joinWith('SpyProductOptionValue.ProductOptionValuePrice')
            ->addAnd(SpyProductOptionGroupTableMap::COL_ACTIVE, true, Criteria::EQUAL)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryProductAbstractOptionsByProductAbstractIds(array $productAbstractIds): SpyProductAbstractProductOptionGroupQuery
    {
        return $this->getFactory()
            ->getProductOptionQuery()
            ->queryAllProductAbstractProductOptionGroups()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productOptionGroupsIds
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryProductAbstractIdsByProductGroupOptionByIds(array $productOptionGroupsIds)
    {
        return $this->getFactory()
            ->getProductOptionQuery()
            ->queryAllProductAbstractProductOptionGroups()
            ->joinSpyProductOptionGroup()
            ->select([SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->filterByFkProductOptionGroup_In($productOptionGroupsIds);
    }

    /**
     * @api
     *
     * @param array $productOptionValueIds
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryProductAbstractIdsByProductValueOptionByIds(array $productOptionValueIds)
    {
        return $this->getFactory()
            ->getProductOptionQuery()
            ->queryAllProductAbstractProductOptionGroups()
            ->joinSpyProductOptionGroup()
            ->joinWith('SpyProductOptionGroup.SpyProductOptionValue')
            ->select([SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->addAnd(SpyProductOptionGroupTableMap::COL_ACTIVE, true, Criteria::EQUAL)
            ->addAnd(SpyProductOptionValueTableMap::COL_ID_PRODUCT_OPTION_VALUE, $productOptionValueIds, Criteria::IN);
    }
}
