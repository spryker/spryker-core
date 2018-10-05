<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Communication\Table;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductReviewGui\Communication\Controller\UpdateController;
use Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainer;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ProductReviewTableConstants
{
    public const TABLE_IDENTIFIER = 'product-review-table';

    public const SORT_DESC = TableConfiguration::SORT_DESC;

    public const PARAM_ID = UpdateController::PARAM_ID;

    public const COL_ID_PRODUCT_REVIEW = 'id_product_review';
    public const COL_CREATED = ProductReviewGuiQueryContainer::FIELD_CREATED;
    public const COL_CUSTOMER_NAME = 'customer_name';
    public const COL_NICK_NAME = 'nickname';
    public const COL_PRODUCT_NAME = ProductReviewGuiQueryContainer::FIELD_PRODUCT_NAME;
    public const COL_RATING = 'rating';
    public const COL_STATUS = 'status';
    public const COL_ACTIONS = 'actions';
    public const COL_SHOW_DETAILS = 'show_details';
    public const COL_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_NAME = SpyProductAbstractLocalizedAttributesTableMap::COL_NAME;
    public const COL_CUSTOMER_FIRST_NAME = SpyCustomerTableMap::COL_FIRST_NAME;
    public const COL_CUSTOMER_LAST_NAME = SpyCustomerTableMap::COL_LAST_NAME;
    public const COL_PRODUCT_REVIEW_STATUS_REJECTED = SpyProductReviewTableMap::COL_STATUS_REJECTED;
    public const COL_PRODUCT_REVIEW_STATUS_APPROVED = SpyProductReviewTableMap::COL_STATUS_APPROVED;
    public const COL_PRODUCT_REVIEW_STATUS_PENDING = SpyProductReviewTableMap::COL_STATUS_PENDING;
    public const COL_PRODUCT_REVIEW_GUI_ID_CUSTOMER = ProductReviewGuiQueryContainer::FIELD_ID_CUSTOMER;
    public const COL_PRODUCT_REVIEW_GUI_FIRST_NAME = ProductReviewGuiQueryContainer::FIELD_CUSTOMER_FIRST_NAME;
    public const COL_PRODUCT_REVIEW_GUI_LAST_NAME = ProductReviewGuiQueryContainer::FIELD_CUSTOMER_LAST_NAME;
    public const EXTRA_DETAILS = 'details';
}
