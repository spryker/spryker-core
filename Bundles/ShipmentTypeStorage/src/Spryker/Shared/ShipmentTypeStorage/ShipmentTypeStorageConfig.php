<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ShipmentTypeStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ShipmentTypeStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines resource name, that will be used for key generation.
     *
     * @api
     *
     * @var string
     */
    public const SHIPMENT_TYPE_RESOURCE_NAME = 'shipment_type';

    /**
     * Specification:
     * - This event will be used for `spy_shipment_type` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SHIPMENT_TYPE_CREATE = 'Entity.spy_shipment_type.create';

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
     * - This event will be used for `spy_shipment_type_store` entity changes.
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
     * - This event will be used for `ShipmentType` publish.
     *
     * @api
     *
     * @var string
     */
    public const SHIPMENT_TYPE_PUBLISH = 'ShipmentType.shipment_type.publish';

    /**
     * Specification:
     * - Queue name as used for processing shipment type messages.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_NAME_SYNC_STORAGE_SHIPMENT_TYPE = 'sync.storage.shipment_type';

    /**
     * Specification:
     * - Queue name as used for processing shipment type error messages.
     *
     * @api
     *
     * @var string
     */
    public const SHIPMENT_TYPE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.shipment_type.error';
}
