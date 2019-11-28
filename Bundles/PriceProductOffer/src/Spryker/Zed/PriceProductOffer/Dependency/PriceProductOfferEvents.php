<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Dependency;

class PriceProductOfferEvents
{
    /**
     * Specification
     * - This events will be used for spy_price_product_offer entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_OFFER_CREATE = 'Entity.spy_price_product_offer.create';

    /**
     * Specification
     * - This events will be used for spy_price_product_offer entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_OFFER_UPDATE = 'Entity.spy_price_product_offer.update';

    /**
     * Specification
     * - This events will be used for spy_price_product_offer entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_OFFER_DELETE = 'Entity.spy_price_product_offer.delete';

    /**
     * Specification
     * - This events will be used for spy_price_product_offer publishing.
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH = 'Entity.spy_price_product_offer.publish';

    /**
     * Specification
     * - This events will be used for spy_price_product_offer un-publishing.
     *
     * @api
     */
    public const ENTITY_SPY_PRICE_PRODUCT_OFFER_UNPUBLISH = 'Entity.spy_price_product_offer.unpublish';
}
