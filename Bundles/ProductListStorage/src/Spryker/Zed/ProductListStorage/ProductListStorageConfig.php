<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductListStorageConfig extends AbstractBundleConfig
{
    protected const PUBLISH_PRODUCT_ABSTRACT_CHUNK = 500;
    protected const PUBLISH_PRODUCT_CONCRETE_CHUNK = 500;

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
    public function getProductAbstractProductListSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductConcreteProductListSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getPublishProductAbstractChunkSize(): int
    {
        return static::PUBLISH_PRODUCT_ABSTRACT_CHUNK;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getPublishProductConcreteChunkSize(): int
    {
        return static::PUBLISH_PRODUCT_CONCRETE_CHUNK;
    }
}
