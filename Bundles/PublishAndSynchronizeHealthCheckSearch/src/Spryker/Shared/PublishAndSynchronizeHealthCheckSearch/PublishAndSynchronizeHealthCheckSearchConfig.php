<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PublishAndSynchronizeHealthCheckSearch;

class PublishAndSynchronizeHealthCheckSearchConfig
{
    /**
     * Defines queue name as used for processing.
     *
     * @api
     */
    public const SYNC_SEARCH_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK = 'sync.search.publish_and_synchronize_health_check';

    /**
     * Specification
     * - This events will be used for spy_publish_and_synchronize_health_check entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_CREATE = 'Entity.spy_publish_and_synchronize_health_check.create';

    /**
     * Specification
     * - This events will be used for spy_publish_and_synchronize_health_check_key entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_UPDATE = 'Entity.spy_publish_and_synchronize_health_check.update';

    /**
     * Specification:
     * - The search key for the data to run validation against.
     *
     * @api
     */
    public const PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_SEARCH_ID = 'publish-and-synchronize:search:health-check';
}
