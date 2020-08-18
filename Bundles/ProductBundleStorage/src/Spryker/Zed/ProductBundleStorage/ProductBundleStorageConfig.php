<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductBundleStorageConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string|null
     */
    public function getProductBundleSynchronizationPoolName(): ?string
    {
        return null;
    }
}
