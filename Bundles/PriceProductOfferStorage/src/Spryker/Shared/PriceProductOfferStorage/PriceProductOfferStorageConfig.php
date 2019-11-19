<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductOfferStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class PriceProductOfferStorageConfig
{
    /**
     * Specification:
     * - Dimension type as used for product offer price.
     *
     * @api
     */
    public const DIMENSION_TYPE = 'OFFER';

    /**
     * Specification:
     * - Queue name as used for processing price product offer messages.
     *
     * @api
     */
    public const PRICE_PRODUCT_OFFER_OFFER_SYNC_STORAGE_QUEUE = 'sync.storage.price_product_offer';

    /**
     * Specification:
     * - Queue name as used for processing price product offer messages.
     *
     * @api
     */
    public const PRICE_PRODUCT_OFFER_OFFER_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.price_product_offer.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     */
    public const RESOURCE_PRICE_PRODUCT_OFFER_OFFER_NAME = 'product_concrete_product_offer_price';
}
