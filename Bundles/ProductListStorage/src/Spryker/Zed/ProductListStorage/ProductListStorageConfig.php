<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductListStorageConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const PRODUCT_LIST_PRODUCT_ABSTRACT_PUBLISH_CHUNK_SIZE = 500;

    /**
     * @var int
     */
    protected const PRODUCT_LIST_PRODUCT_CONCRETE_PUBLISH_CHUNK_SIZE = 500;

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
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
     * @return string|null
     */
    public function getProductAbstractProductListEventQueueName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductConcreteProductListEventQueueName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProductListProductAbstractPublishChunkSize(): int
    {
        return static::PRODUCT_LIST_PRODUCT_ABSTRACT_PUBLISH_CHUNK_SIZE;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProductListProductConcretePublishChunkSize(): int
    {
        return static::PRODUCT_LIST_PRODUCT_CONCRETE_PUBLISH_CHUNK_SIZE;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductListEventQueueName(): ?string
    {
        return null;
    }
}
