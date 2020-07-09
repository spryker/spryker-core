<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferAvailabilityStorageConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return false;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductOfferAvailabilitySynchronizationPoolName(): ?string
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
}
