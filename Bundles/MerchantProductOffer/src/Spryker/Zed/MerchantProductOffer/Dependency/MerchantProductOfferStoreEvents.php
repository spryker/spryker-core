<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Dependency;

interface MerchantProductOfferStoreEvents
{
    /**
     * Specification
     * - This events will be used for merchant product offer store key publishing.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_OFFER_STORE_KEY_PUBLISH = 'MerchantProductOfferStore.key.publish';

    /**
     * Specification
     * - This events will be used for merchant product offer store key un-publishing.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_OFFER_STORE_KEY_UNPUBLISH = 'MerchantProductOfferStore.key.unpublish';

    /**
     * Specification
     * - This events will be used for spy_product_offer_store entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE = 'Entity.spy_product_offer_store.create';

    /**
     * Specification
     * - This events will be used for spy_product_offer_store entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_UPDATE = 'Entity.spy_product_offer_store.update';

    /**
     * Specification
     * - This events will be used for spy_product_offer_store entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE = 'Entity.spy_product_offer_store.delete';
}
