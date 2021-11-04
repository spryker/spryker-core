<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PublishAndSynchronizeHealthCheckStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class PublishAndSynchronizeHealthCheckStorageConfig extends AbstractBundleConfig
{
    /**
     * Defines queue name for processing synchronize.
     *
     * @api
     *
     * @var string
     */
    public const SYNC_STORAGE_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK = 'sync.storage.publish_and_synchronize_health_check';

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
     * - This events will be used for spy_publish_and_synchronize_health_check entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_UPDATE = 'Entity.spy_publish_and_synchronize_health_check.update';

    /**
     * Specification:
     * - The storage key for the data to run validation against.
     *
     * @api
     *
     * @var string
     */
    public const PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_STORAGE_KEY = 'publish_and_synchronize_health_check:health-check';
}
