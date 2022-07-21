<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Dependency\Client;

interface PriceProductOfferStorageToStorageClientInterface
{
    /**
     * @param string $key
     *
     * @return array|null
     */
    public function get($key): ?array;
}
