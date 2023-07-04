<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductOfferServicePointStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ProductOfferServicePointStorageConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Defines resource name, that will be used for key generation.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_SERVICE_RESOURCE_NAME = 'product_offer_service';

    /**
     * Specification:
     * - This event will be used for `spy_product_offer_service` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_SERVICE_CREATE = 'Entity.spy_product_offer_service.create';

    /**
     * Specification:
     * - This event will be used for `spy_product_offer_service` entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_SERVICE_DELETE = 'Entity.spy_product_offer_service.delete';

    /**
     * Specification:
     * - This event will be used for `ProductOfferService` publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_SERVICE_PUBLISH = 'ProductOfferService.product_offer_service.publish';

    /**
     * Specification:
     * - This event will be used for `spy_service` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_CREATE = 'Entity.spy_service.create';

    /**
     * Specification:
     * - This event will be used for `spy_service` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_UPDATE = 'Entity.spy_service.update';

    /**
     * Specification:
     * - This event will be used for `spy_service_point` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_UPDATE = 'Entity.spy_service_point.update';

    /**
     * Specification:
     * - This event will be used for `ServicePoint` publishing.
     *
     * @api
     *
     * @var string
     */
    public const SERVICE_POINT_PUBLISH = 'ServicePoint.service_point.publish';

    /**
     * Specification:
     * - This event will be used for `spy_service_point_store` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_STORE_CREATE = 'Entity.spy_service_point_store.create';

    /**
     * Specification:
     * - This event will be used for `spy_service_point_store` entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_STORE_DELETE = 'Entity.spy_service_point_store.delete';

    /**
     * Specification
     * - This event will be used for `spy_product_offer` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_UPDATE = 'Entity.spy_product_offer.update';

    /**
     * Specification
     * - This event will be used for `ProductOffer` publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_PUBLISH = 'ProductOffer.product_offer.publish';

    /**
     * Specification
     * - This event will be used for `ProductOffer` un-publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_UNPUBLISH = 'ProductOffer.product_offer.unpublish';

    /**
     * Specification
     * - This event will be used for `spy_product_offer_store` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE = 'Entity.spy_product_offer_store.create';

    /**
     * Specification
     * - This event will be used for `spy_product_offer_store` entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE = 'Entity.spy_product_offer_store.delete';

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
     * - Queue name as used for processing product offer service messages.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_NAME_SYNC_STORAGE_PRODUCT_OFFER_SERVICE = 'sync.storage.product_offer_service';

    /**
     * Specification:
     * - Queue name as used for processing product offer service error messages.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_OFFER_SERVICE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product_offer_service.error';
}
