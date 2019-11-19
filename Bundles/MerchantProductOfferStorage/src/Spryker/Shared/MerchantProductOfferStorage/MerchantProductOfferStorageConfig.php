<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantProductOfferStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class MerchantProductOfferStorageConfig
{
    /**
     * Specification:
     * - Queue name as used for processing merchant product offer messages.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_OFFER_SYNC_STORAGE_QUEUE = 'sync.storage.merchant_product_offer';

    /**
     * Specification:
     * - Queue name as used for processing merchant product offer messages.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_OFFER_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.merchant_product_offer.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     */
    public const RESOURCE_MERCHANT_PRODUCT_OFFER_NAME = 'product_offer';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     */
    public const RESOURCE_CONCRETE_PRODUCT_PRODUCT_OFFERS_NAME = 'product_concrete_product_offers';
}
