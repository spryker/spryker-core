<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelCollector\Persistence\Collector\Propel;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

class ProductAbstractRelationCollectorQuery extends AbstractPropelCollectorQuery
{
    const RESULT_FIELD_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const RESULT_FIELD_ID_PRODUCT_LABELS_CSV = 'id_product_labels_csv';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT
        );
        $this->touchQuery->addJoin(
            SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_LABEL,
            SpyProductLabelTableMap::COL_ID_PRODUCT_LABEL
        );
        $this->touchQuery->withColumn(
            SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT,
            static::RESULT_FIELD_ID_PRODUCT_ABSTRACT
        );
        $this->touchQuery->withColumn(
            sprintf(
                'GROUP_CONCAT(%s ORDER BY %s %s)',
                SpyProductLabelTableMap::COL_ID_PRODUCT_LABEL,
                SpyProductLabelTableMap::COL_POSITION,
                Criteria::ASC
            ),
            static::RESULT_FIELD_ID_PRODUCT_LABELS_CSV
        );
        $this->touchQuery->groupBy(SpyTouchTableMap::COL_ITEM_ID);
    }
}
