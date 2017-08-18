<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewCollector\Persistence\Search\Propel;

use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

class ProductReviewSearchCollectorQuery extends AbstractPropelCollectorQuery
{

    const FIELD_ID_PRODUCT_REVIEW = 'id_product_review';
    const FIELD_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    const FIELD_FK_LOCALE = 'fk_locale';
    const FIELD_CUSTOMER_REFERENCE = 'customer_reference';
    const FIELD_CREATED_AT = 'created_at';
    const FIELD_RATING = 'rating';
    const FIELD_NICKNAME = 'nickname';
    const FIELD_SUMMARY = 'summary';
    const FIELD_DESCRIPTION = 'description';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery
            ->addJoin(
                [SpyTouchTableMap::COL_ITEM_ID, SpyLocaleTableMap::COL_ID_LOCALE, SpyLocaleTableMap::COL_LOCALE_NAME],
                [SpyProductReviewTableMap::COL_ID_PRODUCT_REVIEW, SpyProductReviewTableMap::COL_FK_LOCALE, $this->getLocale()],
                Criteria::INNER_JOIN
            );

        $this->touchQuery
            ->withColumn(SpyProductReviewTableMap::COL_ID_PRODUCT_REVIEW, static::FIELD_ID_PRODUCT_REVIEW)
            ->withColumn(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT, static::FIELD_FK_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductReviewTableMap::COL_FK_LOCALE, static::FIELD_FK_LOCALE)
            ->withColumn(SpyProductReviewTableMap::COL_CUSTOMER_REFERENCE, static::FIELD_CUSTOMER_REFERENCE)
            ->withColumn(SpyProductReviewTableMap::COL_CREATED_AT, static::FIELD_CREATED_AT)
            ->withColumn(SpyProductReviewTableMap::COL_RATING, static::FIELD_RATING)
            ->withColumn(SpyProductReviewTableMap::COL_NICKNAME, static::FIELD_NICKNAME)
            ->withColumn(SpyProductReviewTableMap::COL_SUMMARY, static::FIELD_SUMMARY)
            ->withColumn(SpyProductReviewTableMap::COL_DESCRIPTION, static::FIELD_DESCRIPTION);
    }

}
