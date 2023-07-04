<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferServicePointStorageConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const READ_COLLECTION_BATCH_SIZE = 1000;

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductOfferServiceSynchronizationPoolName(): ?string
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
     * @api
     *
     * @return int
     */
    public function getReadCollectionBatchSize(): int
    {
        return static::READ_COLLECTION_BATCH_SIZE;
    }
}
