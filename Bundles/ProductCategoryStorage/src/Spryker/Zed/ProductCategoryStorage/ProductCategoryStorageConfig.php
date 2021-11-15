<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage;

use Spryker\Shared\ProductCategoryStorage\ProductCategoryStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductCategoryStorageConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const WRITE_COLLECTION_DEFAULT_BATCH_SIZE = 1000;

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
    public function getProductCategorySynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getEventQueueName(): ?string
    {
        return null;
    }

    /**
     * Specification:
     * - Returns the number of product abstract ids in the batch to be written.
     *
     * @api
     *
     * @return int
     */
    public function getWriteCollectionBatchSize(): int
    {
        return $this->get(
            ProductCategoryStorageConstants::WRITE_COLLECTION_BATCH_SIZE,
            static::WRITE_COLLECTION_DEFAULT_BATCH_SIZE,
        );
    }
}
