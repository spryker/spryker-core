<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CustomerAccess;

interface CustomerAccessConfig
{
    public const CONTENT_TYPE_PRICE = 'price';
    public const CONTENT_TYPE_ORDER_PLACE_SUBMIT = 'order-place-submit';
    public const CONTENT_TYPE_ADD_TO_CART = 'add-to-cart';
    public const CONTENT_TYPE_WISHLIST = 'wishlist';
    public const CONTENT_TYPE_SHOPPING_LIST = 'shopping-list';
}
