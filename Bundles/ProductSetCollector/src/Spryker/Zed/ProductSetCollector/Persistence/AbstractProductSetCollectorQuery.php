<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Persistence;

use Orm\Zed\ProductSet\Persistence\Map\SpyProductAbstractSetTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetDataTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

abstract class AbstractProductSetCollectorQuery extends AbstractPropelCollectorQuery
{
    public const FIELD_ID_PRODUCT_SET = 'id_product_set';
    public const FIELD_IS_ACTIVE = 'is_active';
    public const FIELD_WEIGHT = 'weight';
    public const FIELD_PRODUCT_SET_KEY = 'product_set_key';
    public const FIELD_NAME = 'name';
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_META_TITLE = 'meta_title';
    public const FIELD_META_KEYWORDS = 'meta_keywords';
    public const FIELD_META_DESCRIPTION = 'meta_description';
    public const FIELD_URL = 'url';
    public const FIELD_ID_PRODUCT_ABSTRACTS = 'id_product_abstracts';

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
            ->withColumn(SpyProductSetTableMap::COL_IS_ACTIVE, static::FIELD_IS_ACTIVE)
            ->withColumn(SpyProductSetTableMap::COL_WEIGHT, static::FIELD_WEIGHT)
            ->withColumn(SpyProductSetTableMap::COL_PRODUCT_SET_KEY, static::FIELD_PRODUCT_SET_KEY)
            ->withColumn(SpyProductSetDataTableMap::COL_NAME, static::FIELD_NAME)
            ->withColumn(SpyProductSetDataTableMap::COL_DESCRIPTION, static::FIELD_DESCRIPTION)
            ->withColumn(SpyProductSetDataTableMap::COL_META_TITLE, static::FIELD_META_TITLE)
            ->withColumn(SpyProductSetDataTableMap::COL_META_KEYWORDS, static::FIELD_META_KEYWORDS)
            ->withColumn(SpyProductSetDataTableMap::COL_META_DESCRIPTION, static::FIELD_META_DESCRIPTION)
            ->withColumn(SpyUrlTableMap::COL_URL, static::FIELD_URL)
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
