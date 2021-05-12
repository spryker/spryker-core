<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ProductConfigurationsRestApiConfig extends AbstractBundleConfig
{
    public const RESPONSE_CODE_DEFAULT_PRODUCT_CONFIGURATION_INSTANCE_IS_MISSING = '4701';

    public const ERROR_MESSAGE_DEFAULT_PRODUCT_CONFIGURATION_INSTANCE_IS_MISSING = 'An item with sku %s can\'t have a configuration with the key %s.';
}
