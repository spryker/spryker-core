<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\PageObject;

class ProductCategoryAssignPage
{
    public const ID_CATEGORY = '2';
    public const URL = '/product-category/assign?id-category=' . self::ID_CATEGORY;
    public const AVAILABLE_PRODUCT_CHECKBOX_SELECTOR_PREFIX = '#all_products_checkbox_';
    public const SELECTED_PRODUCTS_CSV_FIELD_SELECTOR = '#assign_form_products_to_be_assigned';
    public const FORM_SUBMIT_SELECTOR = 'form[name="assign_form"] input[type="submit"]';
    public const SUCCESS_MESSAGE_SELECTOR = '.alert-success';
    public const ASSIGNED_PRODUCT_CHECKBOX_SELECTOR_PREFIX = '#product_category_checkbox_';
    public const DESELECTED_PRODUCTS_CSV_FIELD_SELECTOR = '#assign_form_products_to_be_de_assigned';
    public const SELECTOR_TABLE_SEARCH = '.dataTables_filter input[type="search"]';
    public const CATEGORY_ID = 'id';
    public const PRODUCT_A = 'A';
    public const PRODUCT_B = 'B';
    public const PRODUCT_ID = 'id';

    public const CATEGORY = [
        self::CATEGORY_ID => 2,
    ];

    public const PRODUCTS = [
        self::PRODUCT_A => [
            self::PRODUCT_ID => 1,
        ],
        self::PRODUCT_B => [
            self::PRODUCT_ID => 2,
        ],
    ];
}
