<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupCollector\Persistence\Collector\Propel;

use Orm\Zed\ProductGroup\Persistence\Map\SpyProductAbstractGroupTableMap;
use Orm\Zed\ProductGroup\Persistence\Map\SpyProductGroupTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

class ProductGroupCollectorQuery extends AbstractPropelCollectorQuery
{

    const FIELD_ID_PRODUCT_GROUP = 'id_product_group';
    const FIELD_ID_PRODUCT_ABSTRACTS = 'id_product_abstracts';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyProductGroupTableMap::COL_ID_PRODUCT_GROUP,
            Criteria::INNER_JOIN
        );

        $this->touchQuery->addJoin(
            SpyProductGroupTableMap::COL_ID_PRODUCT_GROUP,
            SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_GROUP,
            Criteria::INNER_JOIN
        );

        $this->touchQuery->withColumn(SpyProductGroupTableMap::COL_ID_PRODUCT_GROUP, self::FIELD_ID_PRODUCT_GROUP);
        $this->touchQuery->withColumn(sprintf(
            'GROUP_CONCAT(%s ORDER BY %s)',
            SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_ABSTRACT,
            SpyProductAbstractGroupTableMap::COL_POSITION
        ), self::FIELD_ID_PRODUCT_ABSTRACTS);

        $this->touchQuery->groupBy(self::FIELD_ID_PRODUCT_GROUP);
    }

}
