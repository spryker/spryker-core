<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductOfferAvailabilityStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductOfferAvailabilityStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Queue name, used for processing product offer availability messages.
     *
     * @api
     */
    public const PRODUCT_OFFER_AVAILABILITY_SYNC_STORAGE_QUEUE = 'sync.storage.product_offer_availability';

    /**
     * Specification:
     * - Queue name, used for processing product offer availability messages.
     *
     * @api
     */
    public const PRODUCT_OFFER_AVAILABILITY_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product_offer_availability.error';

    /**
     * Specification:
     * - Key generation resource name for product offer availability messages.
     *
     * @api
     */
    public const PRODUCT_OFFER_AVAILABILITY_RESOURCE_NAME = 'product_offer_availability';
}
