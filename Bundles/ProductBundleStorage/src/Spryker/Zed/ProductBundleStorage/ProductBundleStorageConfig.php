<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
