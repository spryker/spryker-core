<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage\Dependency\Client;

interface ProductBundleStorageToStorageClientInterface
{
    /**
     * @param string[] $keys
     *
     * @return array
     */
    public function getMulti(array $keys);
}
