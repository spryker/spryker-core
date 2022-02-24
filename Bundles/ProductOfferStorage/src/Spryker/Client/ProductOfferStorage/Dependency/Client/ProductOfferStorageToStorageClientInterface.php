<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferStorage\Dependency\Client;

interface ProductOfferStorageToStorageClientInterface
{
    /**
     * @param array<string> $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array;
}
