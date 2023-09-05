<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage\Generator;

interface StorageKeyGeneratorInterface
{
    /**
     * @param list<int> $resourceIds
     * @param string $resourceName
     * @param string|null $storeName
     *
     * @return list<string>
     */
    public function generateIdKeys(
        array $resourceIds,
        string $resourceName,
        ?string $storeName = null
    ): array;

    /**
     * @param list<string> $uuids
     * @param string $resourceName
     * @param string|null $storeName
     *
     * @return list<string>
     */
    public function generateUuidKeys(
        array $uuids,
        string $resourceName,
        ?string $storeName = null
    ): array;
}
