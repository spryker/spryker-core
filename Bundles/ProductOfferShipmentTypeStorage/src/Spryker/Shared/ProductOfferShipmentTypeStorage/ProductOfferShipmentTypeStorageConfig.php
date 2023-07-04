<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductOfferShipmentTypeStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductOfferShipmentTypeStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - This event will be used for `spy_shipment_type_store` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SHIPMENT_TYPE_STORE_CREATE = 'Entity.spy_shipment_type_store.create';

    /**
     * Specification:
     * - This event will be used for `spy_shipment_type_store` entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SHIPMENT_TYPE_STORE_DELETE = 'Entity.spy_shipment_type_store.delete';

    /**
     * Specification:
     * - This event will be used for `spy_shipment_type` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SHIPMENT_TYPE_UPDATE = 'Entity.spy_shipment_type.update';

    /**
     * Specification:
     * - This event will be used for `spy_product_offer_shipment_type` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_SHIPMENT_TYPE_CREATE = 'Entity.spy_product_offer_shipment_type.create';

    /**
     * Specification:
     * - This event will be used for `spy_product_offer_shipment_type` entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_SHIPMENT_TYPE_DELETE = 'Entity.spy_product_offer_shipment_type.delete';

    /**
     * Specification:
     * - This event will be used for `spy_product_offer` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_UPDATE = 'Entity.spy_product_offer.update';

    /**
     * Specification:
     * - This event will be used for `spy_product_offer_store` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE = 'Entity.spy_product_offer_store.create';

    /**
     * Specification:
     * - This event will be used for `spy_product_offer_store` entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE = 'Entity.spy_product_offer_store.delete';

    /**
     * Specification:
     * - This event will be used for `ProductOffer` publish.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_PUBLISH = 'ProductOffer.product_offer.publish';

    /**
     * Specification:
     * - This event will be used for `ProductOffer` unpublish.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_UNPUBLISH = 'ProductOffer.product_offer.unpublish';

    /**
     * Specification:
     * - This event will be used for `ProductOfferShipmentType` publish.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_SHIPMENT_TYPE_PUBLISH = 'ProductOfferShipmentType.product_offer_shipment_type.publish';

    /**
     * Specification:
     * - This event will be used for `ShipmentType` publish.
     *
     * @api
     *
     * @var string
     */
    public const SHIPMENT_TYPE_PUBLISH = 'ShipmentType.shipment_type.publish';

    /**
     * Specification:
     * - Key generation resource name for product offer shipment type messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_SHIPMENT_TYPE_RESOURCE_NAME = 'product_offer_shipment_type';

    /**
     * Specification:
     * - Queue name as used for processing product offer shipment type messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_SHIPMENT_TYPE_SYNC_STORAGE_QUEUE = 'sync.storage.product_offer_shipment_type';
}
