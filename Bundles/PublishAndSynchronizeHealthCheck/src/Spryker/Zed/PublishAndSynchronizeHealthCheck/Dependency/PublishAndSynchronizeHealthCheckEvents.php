<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Dependency;

interface PublishAndSynchronizeHealthCheckEvents
{
    /**
     * Specification
     * - This events will be used for spy_publish_and_synchronize_health_check entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_CREATE = 'Entity.spy_publish_and_synchronize_health_check.create';

    /**
     * Specification
     * - This events will be used for spy_publish_and_synchronize_health_check_key entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_UPDATE = 'Entity.spy_publish_and_synchronize_health_check.update';

    /**
     * Specification
     * - This events will be used for spy_publish_and_synchronize_health_check_key entity deletion.

     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_DELETE = 'Entity.spy_publish_and_synchronize_health_check.delete';
}
