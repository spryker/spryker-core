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
     *
     * @var string
     */
    public const SESSION_KEY = 'PRODUCT_CONFIGURATION';
}
