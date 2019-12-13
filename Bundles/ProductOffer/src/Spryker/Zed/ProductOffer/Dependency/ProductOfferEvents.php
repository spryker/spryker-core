<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Dependency;

interface ProductOfferEvents
{
    /**
     * Specification:
     * - Represents product offer publish.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_PUBLISH = 'ProductOffer.spy_product_offer.publish';

    /**
     * Specification:
     * - Represents spy_product_offer entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_CREATE = 'Entity.spy_product_offer.create';

    /**
     * Specification:
     * - Represents spy_product_offer entity update.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_UPDATE = 'Entity.spy_product_offer.update';
}
