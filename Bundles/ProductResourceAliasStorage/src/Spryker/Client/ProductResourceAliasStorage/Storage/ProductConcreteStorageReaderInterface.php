<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductResourceAliasStorage\Storage;

interface ProductConcreteStorageReaderInterface
{
    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageData(string $sku, string $localeName): ?array;
}
