<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache;

use Spryker\Shared\PropelReplicationCache\PropelReplicationCacheConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PropelReplicationCacheConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Gets TTL value in seconds for cache keys lifetime.
     *
     * @api
     *
     * @return int
     */
    public function getCacheTTL(): int
    {
        return $this->get(PropelReplicationCacheConstants::CACHE_TTL, 2);
    }

    /**
     * Specification:
     * - Checks is database replication is enabled across the project.
     *
     * @api
     *
     * @return bool
     */
    public function isReplicationEnabled(): bool
    {
        return $this->get(PropelReplicationCacheConstants::IS_REPLICATION_ENABLED, false);
    }
}
