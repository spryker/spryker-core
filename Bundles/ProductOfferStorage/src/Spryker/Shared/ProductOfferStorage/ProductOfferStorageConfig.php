<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductOfferStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class ProductOfferStorageConfig
{
    /**
     * Specification:
     * - Product offer reference attribute as used for selected attributes.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_REFERENCE_ATTRIBUTE = 'product_offer_reference';

    /**
     * Specification
     * - This events will be used for spy_product_offer entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_CREATE = 'Entity.spy_product_offer.create';

    /**
     * Specification
     * - This events will be used for spy_product_offer entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_UPDATE = 'Entity.spy_product_offer.update';

    /**
     * Specification
     * - This events will be used for spy_product_offer entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_DELETE = 'Entity.spy_product_offer.delete';

    /**
     * Specification
     * - This events will be used for spy_product_offer_store entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE = 'Entity.spy_product_offer_store.create';

    /**
     * Specification
     * - This events will be used for spy_product_offer_store entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_UPDATE = 'Entity.spy_product_offer_store.update';

    /**
     * Specification
     * - This events will be used for spy_product_offer_store entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE = 'Entity.spy_product_offer_store.delete';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_PRODUCT_CONCRETE_PRODUCT_OFFERS_NAME = 'product_concrete_product_offers';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_PRODUCT_OFFER_NAME = 'product_offer';

    /**
     * Specification
     * - These events will be used for product offer publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_PUBLISH = 'ProductOffer.product_offer.publish';

    /**
     * Specification
     * - These events will be used for product offer un-publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_UNPUBLISH = 'ProductOffer.product_offer.unpublish';

    /**
     * Specification
     * - These events will be used for product offer store publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_STORE_PUBLISH = 'ProductOfferStore.publish';

    /**
     * Specification
     * - These events will be used for product offer store un-publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_STORE_UNPUBLISH = 'ProductOfferStore.unpublish';

    /**
     * Specification:
     * - Queue name as used for processing product offer messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_SYNC_STORAGE_QUEUE = 'sync.storage.product_offer';

    /**
     * Specification:
     * - Queue name as used for processing product offer messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product_offer.error';
}
