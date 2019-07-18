<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupCollector\Persistence\Collector\Propel;

use Orm\Zed\ProductGroup\Persistence\Map\SpyProductAbstractGroupTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

class ProductAbstractGroupsCollectorQuery extends AbstractPropelCollectorQuery
{
    public const FIELD_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    public const FIELD_ID_PRODUCT_GROUPS = 'id_product_groups';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_ABSTRACT,
            Criteria::INNER_JOIN
        );

        $this->touchQuery->withColumn(SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_ABSTRACT, static::FIELD_ID_PRODUCT_ABSTRACT);
        $this->touchQuery->withColumn(sprintf(
            'GROUP_CONCAT(%s ORDER BY %s)',
            SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_GROUP,
            SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_GROUP
        ), static::FIELD_ID_PRODUCT_GROUPS);

        $this->touchQuery->groupBy(static::FIELD_ID_PRODUCT_ABSTRACT);
    }
}
