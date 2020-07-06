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

    protected const PRODUCT_CONCRETE_PAGE_PUBLISH_CHUNK_SIZE = 500;
    protected const PRODUCT_ABSTRACT_PAGE_PUBLISH_CHUNK_SIZE = 500;

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
    public function getProductPageSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProductConcretePagePublishChunkSize(): int
    {
        return static::PRODUCT_CONCRETE_PAGE_PUBLISH_CHUNK_SIZE;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProductAbstractPagePublishChunkSize(): int
    {
        return static::PRODUCT_ABSTRACT_PAGE_PUBLISH_CHUNK_SIZE;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductPageEventQueueName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductConcretePageEventQueueName(): ?string
    {
        return null;
    }

    /**
     * Specification:
     * - Controls if the "add_to_cart_sku" property is populated for product abstract search entity.
     *
     * @api
     *
     * @return bool
     */
    public function isProductAbstractAddToCartEnabled(): bool
    {
        return false;
    }
}
