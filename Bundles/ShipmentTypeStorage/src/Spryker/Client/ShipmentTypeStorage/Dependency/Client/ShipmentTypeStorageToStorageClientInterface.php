<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage\Dependency\Client;

use Generated\Shared\Transfer\StorageScanResultTransfer;

interface ShipmentTypeStorageToStorageClientInterface
{
    /**
     * @param list<string> $keys
     *
     * @return array<string, string|null>
     */
    public function getMulti(array $keys): array;

    /**
     * @param string $pattern
     * @param int $limit
     * @param int|null $cursor
     *
     * @throws \Spryker\Client\Storage\Exception\InvalidStorageScanPluginInterfaceException
     *
     * @return \Generated\Shared\Transfer\StorageScanResultTransfer
     */
    public function scanKeys(string $pattern, int $limit, ?int $cursor = 0): StorageScanResultTransfer;

    /**
     * @param string $pattern
     *
     * @return array<string, string|null>
     */
    public function getKeys(string $pattern): array;
}
