<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantProductOfferSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class MerchantProductOfferSearchConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_MERCHANT_DATA = 'PLUGIN_PRODUCT_MERCHANT_DATA';

    /**
     * Specification
     * - This events will be used for spy_product_offer entity creation.
     *
     * @api
     *
     * @uses \Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents::ENTITY_SPY_PRODUCT_OFFER_CREATE
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
     * - Merchant product offer resource name, used for key generating.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PRODUCT_OFFER_RESOURCE_NAME = 'merchant_product_offer_search';

    /**
     * Specification:
     * - This event is used for merchant product offer publishing.
     *
     * @api
     *
     * @uses {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::PRODUCT_OFFER_PUBLISH}
     *
     * @var string
     */
    public const PRODUCT_OFFER_PUBLISH = 'ProductOffer.product_offer.publish';
}
