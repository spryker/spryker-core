<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\PageObject;

class ProductCategoryAssignPage
{
    /**
     * @var string
     */
    public const ID_CATEGORY = '2';

    /**
     * @var string
     */
    public const URL = '/product-category/assign?id-category=' . self::ID_CATEGORY;

    /**
     * @var string
     */
    public const AVAILABLE_PRODUCT_CHECKBOX_SELECTOR_PREFIX = '#all_products_checkbox_';

    /**
     * @var string
     */
    public const SELECTED_PRODUCTS_CSV_FIELD_SELECTOR = '#assign_form_products_to_be_assigned';

    /**
     * @var string
     */
    public const FORM_SUBMIT_SELECTOR = 'form[name="assign_form"] input[type="submit"]';

    /**
     * @var string
     */
    public const SUCCESS_MESSAGE_SELECTOR = '.alert-success';

    /**
     * @var string
     */
    public const ASSIGNED_PRODUCT_CHECKBOX_SELECTOR_PREFIX = '#product_category_checkbox_';

    /**
     * @var string
     */
    public const DESELECTED_PRODUCTS_CSV_FIELD_SELECTOR = '#assign_form_products_to_be_de_assigned';

    /**
     * @var string
     */
    public const SELECTOR_TABLE_SEARCH = '.dataTables_filter input[type="search"]';

    /**
     * @var string
     */
    public const CATEGORY_ID = 'id';

    /**
     * @var string
     */
    public const PRODUCT_A = 'A';

    /**
     * @var string
     */
    public const PRODUCT_B = 'B';

    /**
     * @var string
     */
    public const PRODUCT_ID = 'id';

    /**
     * @var array<string, int>
     */
    public const CATEGORY = [
        self::CATEGORY_ID => 2,
    ];

    /**
     * @var array<string, array<string, int>>
     */
    public const PRODUCTS = [
        self::PRODUCT_A => [
            self::PRODUCT_ID => 1,
        ],
        self::PRODUCT_B => [
            self::PRODUCT_ID => 2,
        ],
    ];
}
