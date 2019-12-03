<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductPageSearchConfig extends AbstractBundleConfig
{
    public const PRODUCT_ABSTRACT_RESOURCE_NAME = 'product_abstract';

    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
    }

    /**
     * @return string|null
     */
    public function getProductPageSynchronizationPoolName(): ?string
    {
        return null;
    }
}
