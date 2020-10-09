<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage;

use Spryker\Client\Kernel\AbstractBundleConfig;

class ProductConfigurationStorageConfig extends AbstractBundleConfig
{
    /**
     * Product configuration session key
     */
    public const SESSION_KEY = 'PRODUCT_CONFIGURATION';

    /**
     * @uses \Spryker\Shared\ProductConfiguration\ProductConfigurationConfig::SOURCE_TYPE_PDP
     */
    public const SOURCE_TYPE_PDP = 'SOURCE_TYPE_PDP';

    /**
     * @uses \Spryker\Shared\ProductConfiguration\ProductConfigurationConfig::SOURCE_TYPE_PDP
     */
    public const SOURCE_TYPE_CART = 'SOURCE_TYPE_CART';
}
