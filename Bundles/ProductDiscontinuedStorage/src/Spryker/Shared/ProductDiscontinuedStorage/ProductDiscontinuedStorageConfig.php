<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductDiscontinuedStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductDiscontinuedStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Key generation resource name of product discontinued.
     *
     * @api
     */
    public const PRODUCT_DISCONTINUED_RESOURCE_NAME = 'product_discontinued';
}
