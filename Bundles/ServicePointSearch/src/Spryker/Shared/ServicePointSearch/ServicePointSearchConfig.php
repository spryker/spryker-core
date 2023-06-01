<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ServicePointSearch;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ServicePointSearchConfig extends AbstractBundleConfig
{
    /**
     * Specification
     * - Defines resource name, that will be used for key generation.
     *
     * @api
     *
     * @var string
     */
    public const SERVICE_POINT_RESOURCE_NAME = 'service_point';

    /**
     * Specification
     * - Defines queue name as used for processing translation messages.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_NAME_SYNC_SEARCH_SERVICE_POINT = 'sync.search.service_point';

    /**
     * Specification
     * - Defines queue name as used for processing translation error messages.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_NAME_SYNC_SEARCH_SERVICE_POINT_ERROR = 'sync.search.service_point.error';

    /**
     * Specification
     * - This event will be used for `spy_service_point` publishing.
     *
     * @api
     *
     * @var string
     */
    public const SERVICE_POINT_PUBLISH = 'ServicePoint.service_point.publish';

    /**
     * Specification
     * - This event will be used for `spy_service_point` un-publishing.
     *
     * @api
     *
     * @var string
     */
    public const SERVICE_POINT_UNPUBLISH = 'ServicePoint.service_point.unpublish';

    /**
     * Specification
     * - This event will be used for `spy_service_point` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_CREATE = 'Entity.spy_service_point.create';

    /**
     * Specification
     * - This event will be used for `spy_service_point` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_UPDATE = 'Entity.spy_service_point.update';

    /**
     * Specification
     * - This event will be used for `spy_service_point_address` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_ADDRESS_CREATE = 'Entity.spy_service_point_address.create';

    /**
     * Specification
     * - This event will be used for `spy_service_point_address` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_ADDRESS_UPDATE = 'Entity.spy_service_point_address.update';

    /**
     * Specification
     * - This event will be used for `spy_service_point_store` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_STORE_CREATE = 'Entity.spy_service_point_store.create';

    /**
     * Specification
     * - This event will be used for `spy_service_point_store` entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_POINT_STORE_DELETE = 'Entity.spy_service_point_store.delete';

    /**
     * Specification
     * - This event will be used for `spy_service` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_CREATE = 'Entity.spy_service.create';

    /**
     * Specification
     * - This event will be used for `spy_service` entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_SERVICE_UPDATE = 'Entity.spy_service.update';
}
