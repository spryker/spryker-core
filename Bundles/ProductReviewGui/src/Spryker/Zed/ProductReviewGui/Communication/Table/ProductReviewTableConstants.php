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

interface ProductReviewTableConstants
{

    const TABLE_IDENTIFIER = 'product-review-table';

    const SORT_DESC = TableConfiguration::SORT_DESC;

    const PARAM_ID = UpdateController::PARAM_ID;

    const COL_ID_PRODUCT_REVIEW = 'id_product_review';
    const COL_CREATED = ProductReviewGuiQueryContainer::FIELD_CREATED;
    const COL_CUSTOMER_NAME = 'customer_name';
    const COL_NICK_NAME = 'nickname';
    const COL_PRODUCT_NAME = ProductReviewGuiQueryContainer::FIELD_PRODUCT_NAME;
    const COL_RATING = 'rating';
    const COL_STATUS = 'status';
    const COL_ACTIONS = 'actions';
    const COL_SHOW_DETAILS = 'show_details';
    const COL_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_NAME = SpyProductAbstractLocalizedAttributesTableMap::COL_NAME;
    const COL_CUSTOMER_FIRST_NAME = SpyCustomerTableMap::COL_FIRST_NAME;
    const COL_CUSTOMER_LAST_NAME = SpyCustomerTableMap::COL_LAST_NAME;
    const COL_PRODUCT_REVIEW_STATUS_REJECTED = SpyProductReviewTableMap::COL_STATUS_REJECTED;
    const COL_PRODUCT_REVIEW_STATUS_APPROVED = SpyProductReviewTableMap::COL_STATUS_APPROVED;
    const COL_PRODUCT_REVIEW_STATUS_PENDING = SpyProductReviewTableMap::COL_STATUS_PENDING;
    const COL_PRODUCT_REVIEW_GUI_ID_CUSTOMER = ProductReviewGuiQueryContainer::FIELD_ID_CUSTOMER;
    const COL_PRODUCT_REVIEW_GUI_FIRST_NAME = ProductReviewGuiQueryContainer::FIELD_CUSTOMER_FIRST_NAME;
    const COL_PRODUCT_REVIEW_GUI_LAST_NAME = ProductReviewGuiQueryContainer::FIELD_CUSTOMER_LAST_NAME;
    const EXTRA_DETAILS = 'details';

}
