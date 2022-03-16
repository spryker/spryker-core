<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache\Business;

interface PropelReplicationCacheFacadeInterface
{
    /**
     * Specification:
     * - Sets key to the storage.
     * - Called after Propel save() method was executed when replication is enabled.
     * - Key contains Propel model class name.
     *
     * @api
     *
     * @param string $key
     * @param int|null $ttl
     *
     * @return void
     */
    public function setKey(string $key, ?int $ttl = null): void;

    /**
     * Specification:
     * - Verifies that given key exists in storage.
     * - Called before Propel query will be executed.
     * - Used to decide what connection to use for query when replication is enabled.
     *
     * @api
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool;
}
