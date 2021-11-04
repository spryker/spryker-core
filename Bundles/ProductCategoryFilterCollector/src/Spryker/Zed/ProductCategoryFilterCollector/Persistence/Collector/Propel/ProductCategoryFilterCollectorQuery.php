<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterCollector\Persistence\Collector\Propel;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Orm\Zed\ProductCategoryFilter\Persistence\Map\SpyProductCategoryFilterTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

class ProductCategoryFilterCollectorQuery extends AbstractPropelCollectorQuery
{
    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyProductCategoryFilterTableMap::COL_FK_CATEGORY,
            Criteria::INNER_JOIN,
        );

        $this->touchQuery->withColumn(SpyProductCategoryFilterTableMap::COL_FILTER_DATA, ProductCategoryFilterTransfer::FILTER_DATA);
    }
}
