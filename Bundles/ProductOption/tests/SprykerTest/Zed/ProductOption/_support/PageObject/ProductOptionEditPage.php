<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\PageObject;

class ProductOptionEditPage
{
    /**
     * @var string
     */
    public const URL = '/product-option/edit/index?id-product-option-group=%d';

    /**
     * @var string
     */
    public const PRODUCT_GROUP_EDIT_SUCCESS_MESSAGE = 'Product option group modified.';
    /**
     * @var string
     */
    public const PRODUCT_GROUP_EDIT_ACTIVATE_SUCCESS_MESSAGE = 'Option successfully activated.';
}
