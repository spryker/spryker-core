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
    protected const PUBLISH_PRODUCT_CONCRETE_CHUNK_SIZE = 500;
    protected const REFRESH_PRODUCT_ABSTRACT_CHUNK_SIZE = 500;

    /**
     * @api
     *
     * @deprecated Use `\Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()` instead.
     *
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductPageSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getPublishProductConcreteChunkSize(): int
    {
        return static::PUBLISH_PRODUCT_CONCRETE_CHUNK_SIZE;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getRefreshProductAbstractChunkSize(): int
    {
        return static::REFRESH_PRODUCT_ABSTRACT_CHUNK_SIZE;
    }
}
