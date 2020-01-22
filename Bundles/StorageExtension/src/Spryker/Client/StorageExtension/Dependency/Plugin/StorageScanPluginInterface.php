<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StorageScanResultTransfer;

interface StorageScanPluginInterface extends StoragePluginInterface
{
    /**
     * Specification:
     * - Gets all keys filtered by pattern.
     * - Limit the results (optional).
     * - Supports seek method pagination with a cursor input (optional).
     *
     * @api
     *
     * @param string $pattern
     * @param int $limit
     * @param int|null $cursor
     *
     * @return \Generated\Shared\Transfer\StorageScanResultTransfer
     */
    public function scanKeys(string $pattern, int $limit, ?int $cursor = 0): StorageScanResultTransfer;
}
