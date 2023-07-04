<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ServicePointStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ServicePointStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines resource name, that will be used for key generation.
     *
     * @api
     *
     * @var string
     */
    public const SERVICE_POINT_RESOURCE_NAME = 'service_point';

    /**
     * Specification:
     * - This event will be used for `spy_service_point` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_CREATE = 'Entity.spy_service_point.create';

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
     * - This event will be used for `spy_service_point_address` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_ADDRESS_CREATE = 'Entity.spy_service_point_address.create';

    /**
     * Specification:
     * - This event will be used for `spy_service_point_address` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_ADDRESS_UPDATE = 'Entity.spy_service_point_address.update';

    /**
     * Specification:
     * - This event will be used for `spy_service_point_store` entity changes.
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
     * Specification:
     * - This event will be used for `ServicePoint` publish.
     *
     * @api
     *
     * @var string
     */
    public const SERVICE_POINT_PUBLISH = 'ServicePoint.service_point.publish';

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
     * - Queue name as used for processing service point messages.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_NAME_SYNC_STORAGE_SERVICE_POINT = 'sync.storage.service_point';

    /**
     * Specification:
     * - Queue name as used for processing service point error messages.
     *
     * @api
     *
     * @var string
     */
    public const SERVICE_POINT_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.service_point.error';
}
