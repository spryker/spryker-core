<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageGui\Dependency\Client;

use Generated\Shared\Transfer\StorageScanResultTransfer;

interface StorageGuiToStorageClientInterface
{
    /**
     * @param string $pattern
     * @param int $limit
     * @param int|null $cursor
     *
     * @return \Generated\Shared\Transfer\StorageScanResultTransfer
     */
    public function scanKeys(string $pattern, int $limit, ?int $cursor = 0): StorageScanResultTransfer;

    /**
     * @param string $pattern
     *
     * @return list<string>
     */
    public function getKeys(string $pattern): array;

    /**
     * @param array<string> $keys
     *
     * @return array<string, string>
     */
    public function getMulti(array $keys): array;

    /**
     * @return int
     */
    public function getCountItems(): int;
}
