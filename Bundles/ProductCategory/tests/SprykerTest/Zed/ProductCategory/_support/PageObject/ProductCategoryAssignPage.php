<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\PageObject;

class ProductCategoryAssignPage
{
    const ID_CATEGORY = '2';
    const URL = '/product-category/assign?id-category=' . self::ID_CATEGORY;
    const AVAILABLE_PRODUCT_CHECKBOX_SELECTOR_PREFIX = '#all_products_checkbox_';
    const SELECTED_PRODUCTS_CSV_FIELD_SELECTOR = '#assign_form_products_to_be_assigned';
    const FORM_SUBMIT_SELECTOR = 'form[name="assign_form"] input[type="submit"]';
    const SUCCESS_MESSAGE_SELECTOR = '.alert-success';
    const ASSIGNED_PRODUCT_CHECKBOX_SELECTOR_PREFIX = '#product_category_checkbox_';
    const DESELECTED_PRODUCTS_CSV_FIELD_SELECTOR = '#assign_form_products_to_be_de_assigned';
    const SELECTOR_TABLE_SEARCH = '.dataTables_filter input[type="search"]';
    const CATEGORY_ID = 'id';
    const PRODUCT_A = 'A';
    const PRODUCT_B = 'B';
    const PRODUCT_ID = 'id';

    const CATEGORY = [
        self::CATEGORY_ID => 2,
    ];

    const PRODUCTS = [
        self::PRODUCT_A => [
            self::PRODUCT_ID => 1,
        ],
        self::PRODUCT_B => [
            self::PRODUCT_ID => 2,
        ],
    ];
}
