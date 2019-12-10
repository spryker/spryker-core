<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Dependency;

interface ProductOfferAvailabilityEvents
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

    /**
     * Specification:
     * - Represents spy_product_offer_stock entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STOCK_CREATE = 'Entity.spy_product_offer_stock.create';

    /**
     * Specification:
     * - Represents spy_product_offer_stock entity update.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STOCK_UPDATE = 'Entity.spy_product_offer_stock.update';

    /**
     * Specification:
     * - Represents spy_oms_product_reservation entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_OMS_PRODUCT_RESERVATION_CREATE = 'Entity.spy_product_offer_stock.create';

    /**
     * Specification:
     * - Represents spy_oms_product_reservation entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_OMS_PRODUCT_RESERVATION_DELETE = 'Entity.spy_product_offer_stock.delete';

    /**
     * Specification:
     * - Represents spy_oms_product_reservation entity update.
     *
     * @api
     */
    public const ENTITY_SPY_OMS_PRODUCT_RESERVATION_UPDATE = 'Entity.spy_oms_product_reservation.update';
}
