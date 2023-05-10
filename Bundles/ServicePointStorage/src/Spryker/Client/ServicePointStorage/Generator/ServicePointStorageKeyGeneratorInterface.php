<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage\Generator;

interface ServicePointStorageKeyGeneratorInterface
{
    /**
     * @param list<int> $servicePointIds
     * @param string $storeName
     *
     * @return list<string>
     */
    public function generateServicePointIdKeys(array $servicePointIds, string $storeName): array;

    /**
     * @param list<string> $uuids
     * @param string $storeName
     *
     * @return list<string>
     */
    public function generateUuidKeys(array $uuids, string $storeName): array;
}
