<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Dependency;

/**
 * @deprecated Will be removed next major release.
 */
interface MerchantProductOfferStoreEvents
{
    /**
     * @deprecated Use {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::PRODUCT_OFFER_STORE_PUBLISH}
     *
     * Specification
     * - This events will be used for merchant product offer store key publishing.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PRODUCT_OFFER_STORE_KEY_PUBLISH = 'MerchantProductOfferStore.key.publish';

    /**
     * @deprecated Use {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::PRODUCT_OFFER_STORE_UNPUBLISH}
     *
     * Specification
     * - This events will be used for merchant product offer store key un-publishing.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PRODUCT_OFFER_STORE_KEY_UNPUBLISH = 'MerchantProductOfferStore.key.unpublish';

    /**
     * @deprecated Use {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE}
     *
     * Specification
     * - This events will be used for spy_product_offer_store entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE = 'Entity.spy_product_offer_store.create';

    /**
     * @deprecated Use {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_UPDATE}
     *
     * Specification
     * - This events will be used for spy_product_offer_store entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_UPDATE = 'Entity.spy_product_offer_store.update';

    /**
     * @deprecated Use {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE}
     *
     * Specification
     * - This events will be used for spy_product_offer_store entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE = 'Entity.spy_product_offer_store.delete';
}
