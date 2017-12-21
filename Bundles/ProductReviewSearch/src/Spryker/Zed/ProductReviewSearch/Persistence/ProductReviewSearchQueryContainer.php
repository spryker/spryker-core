<?php

namespace Spryker\Zed\ProductReviewSearch\Persistence;

use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchPersistenceFactory getFactory()
 */
class ProductReviewSearchQueryContainer extends AbstractQueryContainer implements ProductReviewSearchQueryContainerInterface
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
     * @param array $productReviewIds
     *
     * @return $this|\Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearchQuery
     */
    public function queryProductReviewSearchByIds(array $productReviewIds)
    {
        return $this
            ->getFactory()
            ->createSpyProductReviewSearchQuery()
            ->filterByFkProductReview_In($productReviewIds);
    }

    /**
     * @param array $productReviewIds
     *
     * @return SpyProductReviewQuery
     */
    public function queryProductReviewsByIdProductReviews(array $productReviewIds)
    {
        return $this->getFactory()
            ->getProductReviewQuery()
            ->queryProductReview()
            ->filterByIdProductReview_In($productReviewIds);
//            ->withColumn(SpyProductReviewTableMap::COL_ID_PRODUCT_REVIEW, static::FIELD_ID_PRODUCT_REVIEW)
//            ->withColumn(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT, static::FIELD_FK_PRODUCT_ABSTRACT)
//            ->withColumn(SpyProductReviewTableMap::COL_FK_LOCALE, static::FIELD_FK_LOCALE)
//            ->withColumn(SpyProductReviewTableMap::COL_CUSTOMER_REFERENCE, static::FIELD_CUSTOMER_REFERENCE)
//            ->withColumn(SpyProductReviewTableMap::COL_CREATED_AT, static::FIELD_CREATED_AT)
//            ->withColumn(SpyProductReviewTableMap::COL_RATING, static::FIELD_RATING)
//            ->withColumn(SpyProductReviewTableMap::COL_NICKNAME, static::FIELD_NICKNAME)
//            ->withColumn(SpyProductReviewTableMap::COL_SUMMARY, static::FIELD_SUMMARY)
//            ->withColumn(SpyProductReviewTableMap::COL_DESCRIPTION, static::FIELD_DESCRIPTION);
    }
}
