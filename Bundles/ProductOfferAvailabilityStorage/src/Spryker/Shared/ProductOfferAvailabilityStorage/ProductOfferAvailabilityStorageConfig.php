<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     *
     * @var string
     */
    public const PRODUCT_OFFER_AVAILABILITY_SYNC_STORAGE_QUEUE = 'sync.storage.product_offer_availability';

    /**
     * Specification:
     * - Queue name, used for processing product offer availability messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_AVAILABILITY_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product_offer_availability.error';

    /**
     * Specification:
     * - Key generation resource name for product offer availability messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_AVAILABILITY_RESOURCE_NAME = 'product_offer_availability';

    /**
     * Specification
     * - This event will be used for `spy_product_offer_store` entity creation.
     *
     * @uses \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE = 'Entity.spy_product_offer_store.create';

    /**
     * Specification
     * - This event will be used for `spy_product_offer_store` entity changes.
     *
     * @uses \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_UPDATE
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_UPDATE = 'Entity.spy_product_offer_store.update';

    /**
     * Specification
     * - This event will be used for `spy_product_offer_store` entity deletion.
     *
     * @uses \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE = 'Entity.spy_product_offer_store.delete';

    /**
     * Specification
     * - This event will be used for `spy_stock_store` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_STOCK_STORE_CREATE = 'Entity.spy_stock_store.create';

    /**
     * Specification
     * - This event will be used for `spy_stock_store` entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_STOCK_STORE_DELETE = 'Entity.spy_stock_store.delete';

    /**
     * Specification
     * - This event will be used for `spy_stock` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_STOCK_UPDATE = 'Entity.spy_stock.update';
}
