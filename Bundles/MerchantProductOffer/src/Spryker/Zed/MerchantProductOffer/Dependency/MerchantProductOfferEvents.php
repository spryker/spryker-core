<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Dependency;

/**
 * @deprecated Will be removed next major release.
 */
interface MerchantProductOfferEvents
{
    /**
     * @deprecated Use {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::PRODUCT_OFFER_PUBLISH}
     *
     * Specification
     * - This events will be used for merchant product offer publishing.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PRODUCT_OFFER_PUBLISH = 'MerchantProductOffer.product_offer.publish';

    /**
     * @deprecated Use {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::PRODUCT_OFFER_UNPUBLISH}
     *
     * Specification
     * - This events will be used for merchant product offer un-publishing.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PRODUCT_OFFER_UNPUBLISH = 'MerchantProductOffer.product_offer.unpublish';

    /**
     * @deprecated Use {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_CREATE}
     *
     * Specification
     * - This events will be used for spy_product_offer entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_CREATE = 'Entity.spy_product_offer.create';

    /**
     * @deprecated Use {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_UPDATE}
     *
     * Specification
     * - This events will be used for spy_product_offer entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_UPDATE = 'Entity.spy_product_offer.update';

    /**
     * @deprecated Use {@link \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::ENTITY_SPY_PRODUCT_OFFER_DELETE}
     *
     * Specification
     * - This events will be used for spy_product_offer entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_DELETE = 'Entity.spy_product_offer.delete';
}
