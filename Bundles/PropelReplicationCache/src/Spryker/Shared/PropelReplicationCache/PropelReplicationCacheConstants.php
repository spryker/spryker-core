<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PropelReplicationCache;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PropelReplicationCacheConstants
{
    /**
     * Specification:
     * - Defines TTL in seconds for keys lifetime in cache storage.
     *
     * Example:
     *
     * $config[PropelReplicationCacheConstants::CACHE_TTL] = 2;
     *
     * @api
     *
     * @var string
     */
    public const CACHE_TTL = 'PROPEL_REPLICA_CACHE:CACHE_TTL';

    /**
     * Specification:
     * - Defines if databae replication is enabled.
     *
     * Example:
     *
     * $config[PropelReplicationCacheConstants::IS_REPLICATION_ENABLED] = (bool)$config[PropelConstants::ZED_DB_REPLICAS];
     *
     * @api
     *
     * @var string
     */
    public const IS_REPLICATION_ENABLED = 'PROPEL_REPLICA_CACHE:IS_REPLICATION_ENABLED';
}
