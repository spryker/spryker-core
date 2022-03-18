<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache\Business\Model;

interface PropelReplicationCacheInterface
{
    /**
     * Specification:
     * - Sets key to the storage.
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
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool;
}
