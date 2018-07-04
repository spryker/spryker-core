<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductListGui;

interface ProductListGuiConstants
{
    public const URL_PARAM_ID_PRODUCT_LIST = 'id-product-list';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    public const REDIRECT_URL_DEFAULT = '/product-list-gui';

    public const MESSAGE_PRODUCT_LIST_CREATE_SUCCESS = 'Product List "%s" has been successfully created.';
    public const MESSAGE_PRODUCT_LIST_UPDATE_SUCCESS = 'Product List "%s" has been successfully updated.';
    public const MESSAGE_PRODUCT_LIST_DELETE_SUCCESS = 'Product List has been successfully removed.';
}
