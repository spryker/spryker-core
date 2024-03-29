<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CartPermissionGroupsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_CART_PERMISSION_GROUPS = 'cart-permission-groups';

    /**
     * @var string
     */
    public const CONTROLLER_CART_PERMISSION_GROUPS = 'cart-permission-groups-resource';

    /**
     * @var string
     */
    public const RESPONSE_CODE_CART_PERMISSION_GROUP_NOT_FOUND = '2501';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_CART_PERMISSION_GROUP_NOT_FOUND = 'Cart permission group not found.';
}
