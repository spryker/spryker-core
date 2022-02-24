<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage\Dependency\Client;

interface MerchantStorageToStorageClientInterface
{
    /**
     * @param string $key
     *
     * @return array|null
     */
    public function get($key): ?array;

    /**
     * @param array<string> $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array;
}
