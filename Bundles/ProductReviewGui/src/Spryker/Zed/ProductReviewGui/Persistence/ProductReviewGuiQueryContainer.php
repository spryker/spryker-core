<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Persistence;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiPersistenceFactory getFactory()
 */
class ProductReviewGuiQueryContainer extends AbstractQueryContainer implements ProductReviewGuiQueryContainerInterface
{
    public const FIELD_PRODUCT_NAME = 'product_name';
    public const FIELD_ID_CUSTOMER = 'id_customer';
    public const FIELD_CUSTOMER_FIRST_NAME = 'first_name';
    public const FIELD_CUSTOMER_LAST_NAME = 'last_name';
    public const FIELD_CREATED = 'created';

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReview($idLocale)
    {
        return $this->getFactory()
            ->getProductReviewQueryContainer()
            ->queryProductReview()
            ->useSpyProductAbstractQuery()
                ->useSpyProductAbstractLocalizedAttributesQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse()
            ->addJoin(SpyProductReviewTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE)
            ->withColumn(SpyProductReviewTableMap::COL_CREATED_AT, static::FIELD_CREATED)
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::FIELD_PRODUCT_NAME)
            ->withColumn(SpyCustomerTableMap::COL_ID_CUSTOMER, static::FIELD_ID_CUSTOMER)
            ->withColumn(SpyCustomerTableMap::COL_FIRST_NAME, static::FIELD_CUSTOMER_FIRST_NAME)
            ->withColumn(SpyCustomerTableMap::COL_LAST_NAME, static::FIELD_CUSTOMER_LAST_NAME);
    }
}
