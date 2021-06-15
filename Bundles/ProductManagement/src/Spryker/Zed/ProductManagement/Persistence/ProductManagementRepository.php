<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementPersistenceFactory getFactory()
 */
class ProductManagementRepository extends AbstractRepository implements ProductManagementRepositoryInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandQuery(ModelCriteria $query): ModelCriteria
    {
        return $this->getFactory()
            ->createProductAbstractQueryExpander()
            ->expandQuery($query);
    }

    /**
     * @return string
     */
    public function createProductAbstractLocalizedAttributesNameSubQuery(): string
    {
        $productAbstractLocalizedAttributesNameSubQuery = $this->getFactory()
            ->createProductAbstractLocalizedAttributesPropelQuery()
            ->where(sprintf(
                '%s NOT LIKE \'\' AND %s = %s',
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT
            ))
            ->limit(1)
            ->addSelectColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME);

        $params = [];

        return $productAbstractLocalizedAttributesNameSubQuery->createSelectSql($params);
    }
}
