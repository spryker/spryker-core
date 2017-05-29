<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Persistence\Search\Propel;

use Orm\Zed\ProductSet\Persistence\Map\SpyProductAbstractSetTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetDataTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

// TODO: extract query to abstract class and reuse for search and storage the same?
class ProductSetCollectorQuery extends AbstractPropelCollectorQuery
{

    const FIELD_ID_PRODUCT_SET = 'id_product_set';
    const FIELD_IS_ACTIVE = 'is_active';
    const FIELD_WEIGHT = 'weight';
    const FIELD_NAME = 'name';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_META_TITLE = 'meta_title';
    const FIELD_META_KEYWORDS = 'meta_keywords';
    const FIELD_META_DESCRIPTION = 'meta_description';
    const FIELD_URL = 'url';
    const FIELD_ID_PRODUCT_ABSTRACTS = 'id_product_abstracts';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery
            ->addJoin(
                SpyTouchTableMap::COL_ITEM_ID,
                SpyProductAbstractSetTableMap::COL_FK_PRODUCT_SET,
                Criteria::INNER_JOIN
            )
            ->addJoin(
                SpyProductAbstractSetTableMap::COL_FK_PRODUCT_SET,
                SpyProductSetTableMap::COL_ID_PRODUCT_SET,
                Criteria::INNER_JOIN
            )
            ->addJoin(
                SpyProductSetTableMap::COL_ID_PRODUCT_SET,
                SpyProductSetDataTableMap::COL_FK_PRODUCT_SET,
                Criteria::INNER_JOIN
            )
            ->addJoin(
                SpyProductSetTableMap::COL_ID_PRODUCT_SET,
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_SET,
                Criteria::INNER_JOIN
            )
            ->add(SpyProductSetDataTableMap::COL_FK_LOCALE, $this->getLocale()->getIdLocale())
            ->add(SpyUrlTableMap::COL_FK_LOCALE, $this->getLocale()->getIdLocale())
            ->addGroupByColumn(SpyProductSetTableMap::COL_ID_PRODUCT_SET);

        $this->touchQuery
            ->withColumn(SpyProductSetTableMap::COL_ID_PRODUCT_SET, static::FIELD_ID_PRODUCT_SET)
            ->withColumn(SpyProductSetTableMap::COL_IS_ACTIVE, self::FIELD_IS_ACTIVE)
            ->withColumn(SpyProductSetTableMap::COL_WEIGHT, self::FIELD_WEIGHT)
            ->withColumn(SpyProductSetDataTableMap::COL_NAME, self::FIELD_NAME)
            ->withColumn(SpyProductSetDataTableMap::COL_DESCRIPTION, self::FIELD_DESCRIPTION)
            ->withColumn(SpyProductSetDataTableMap::COL_META_TITLE, self::FIELD_META_TITLE)
            ->withColumn(SpyProductSetDataTableMap::COL_META_KEYWORDS, self::FIELD_META_KEYWORDS)
            ->withColumn(SpyProductSetDataTableMap::COL_META_DESCRIPTION, self::FIELD_META_DESCRIPTION)
            ->withColumn(SpyUrlTableMap::COL_URL, self::FIELD_URL)
            ->withColumn(
                sprintf(
                    'GROUP_CONCAT(%s ORDER BY %s)',
                    SpyProductAbstractSetTableMap::COL_FK_PRODUCT_ABSTRACT,
                    SpyProductAbstractSetTableMap::COL_POSITION
                ),
                static::FIELD_ID_PRODUCT_ABSTRACTS
            );
    }

}
