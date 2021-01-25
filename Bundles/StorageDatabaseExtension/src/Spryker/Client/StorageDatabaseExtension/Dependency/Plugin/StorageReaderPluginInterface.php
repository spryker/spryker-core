<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabaseExtension\Dependency\Plugin;

interface StorageReaderPluginInterface
{
    /**
     * Specification:
     * - Gets single resource data from storage database by a resource key.
     * - The data is returned as JSON encoded string.
     *
     * @api
     *
     * @param string $resourceKey
     *
     * @return string
     */
    public function get(string $resourceKey): string;

    /**
     * Specification:
     * - Gets data for multiple resources by an array of resource keys.
     *
     * @api
     *
     * @param string[] $resourceKeys
     *
     * @return array
     */
    public function getMulti(array $resourceKeys): array;
}
