<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantProductOfferStorageConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const WRITE_COLLECTION_BY_MERCHANT_EVENTS_BATCH_SIZE = 1000;

    /**
     * Specification:
     * - Returns the number of product offers in the batch to be written by merchant events.
     *
     * @api
     *
     * @return int
     */
    public function getWriteCollectionByMerchantEventsBatchSize(): int
    {
        return static::WRITE_COLLECTION_BY_MERCHANT_EVENTS_BATCH_SIZE;
    }
}
