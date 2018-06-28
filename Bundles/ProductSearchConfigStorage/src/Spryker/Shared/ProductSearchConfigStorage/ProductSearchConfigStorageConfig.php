<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductSearchConfigStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductSearchConfigStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const PRODUCT_SEARCH_CONFIG_EXTENSION_RESOURCE_NAME = 'product_search_config_extension';
}
