<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Dependency;

interface MerchantProductOfferEvents
{
    /**
     * Specification
     * - This events will be used for merchant product offer publishing.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_OFFER_PUBLISH = 'MerchantProductOffer.product_offer.publish';

    /**
     * Specification
     * - This events will be used for merchant product offer un-publishing.
     *
     * @api
     */
    public const MERCHANT_PRODUCT_OFFER_UNPUBLISH = 'MerchantProductOffer.product_offer.unpublish';

    /**
     * Specification
     * - This events will be used for spy_product_offer entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_CREATE = 'Entity.spy_product_offer.create';

    /**
     * Specification
     * - This events will be used for spy_product_offer entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_UPDATE = 'Entity.spy_product_offer.update';

    /**
     * Specification
     * - This events will be used for spy_product_offer entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_DELETE = 'Entity.spy_product_offer.delete';
}
