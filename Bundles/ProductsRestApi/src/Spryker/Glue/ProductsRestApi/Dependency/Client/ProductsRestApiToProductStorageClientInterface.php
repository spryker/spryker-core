<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Dependency\Client;

interface ProductsRestApiToProductStorageClientInterface
{
    /**
     * @param string $mapping
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataByMap(string $mapping, string $identifier, string $localeName): ?array;

    /**
     * @param string $mapping
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMap(string $mapping, string $identifier, string $localeName): ?array;
}
