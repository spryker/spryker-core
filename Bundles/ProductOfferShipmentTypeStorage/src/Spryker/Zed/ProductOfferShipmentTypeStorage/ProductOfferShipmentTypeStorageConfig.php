<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferShipmentTypeStorageConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const READ_COLLECTION_BATCH_SIZE = 500;

    /**
     * Specification:
     * - Returns the number of ProductOfferShipmentType entities in the batch to be read.
     *
     * @api
     *
     * @return int
     */
    public function getReadCollectionBatchSize(): int
    {
        return static::READ_COLLECTION_BATCH_SIZE;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductOfferShipmentTypeSynchronizationPoolName(): ?string
    {
        return null;
    }
}
