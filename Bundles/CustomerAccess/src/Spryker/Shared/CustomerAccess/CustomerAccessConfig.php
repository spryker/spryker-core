<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CustomerAccess;

interface CustomerAccessConfig
{
    /**
     * @var string
     */
    public const CONTENT_TYPE_PRICE = 'price';
    /**
     * @var string
     */
    public const CONTENT_TYPE_ORDER_PLACE_SUBMIT = 'order-place-submit';
    /**
     * @var string
     */
    public const CONTENT_TYPE_ADD_TO_CART = 'add-to-cart';
    /**
     * @var string
     */
    public const CONTENT_TYPE_WISHLIST = 'wishlist';
    /**
     * @var string
     */
    public const CONTENT_TYPE_SHOPPING_LIST = 'shopping-list';
}
