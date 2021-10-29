<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationWishlistsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductConfigurationWishlistsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESPONSE_CODE_DEFAULT_PRODUCT_CONFIGURATION_INSTANCE_IS_MISSING = '4701';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_DEFAULT_PRODUCT_CONFIGURATION_INSTANCE_IS_MISSING = 'An item with sku %s can\'t have a configuration with the key %s.';
}
