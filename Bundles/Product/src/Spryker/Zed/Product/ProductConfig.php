<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product;

use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Product\Dependency\ProductEvents;

class ProductConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const FILTERED_PRODUCTS_LIMIT_DEFAULT = 10;

    /**
     * @api
     *
     * @return int
     */
    public function getFilteredProductsLimitDefault(): int
    {
        return static::FILTERED_PRODUCTS_LIMIT_DEFAULT;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProductExportPublishChunkSize(): int
    {
        return 5000;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProductPublishToMessageBrokerChunkSize(): int
    {
        return 5;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getProductAbstractUpdateMessageBrokerPublisherSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getProductUpdateMessageBrokerPublisherSubscribedEvents(): array
    {
        return [
            ProductEvents::ENTITY_SPY_PRODUCT_UPDATE,
            ProductEvents::PRODUCT_CONCRETE_UPDATE,
            ProductEvents::PRODUCT_CONCRETE_PUBLISH,
        ];
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isPublishingToMessageBrokerEnabled(): bool
    {
        return (bool)$this->get(ProductConstants::PUBLISHING_TO_MESSAGE_BROKER_ENABLED, true);
    }
}
