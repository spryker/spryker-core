<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi\Dependency\Client;

interface ProductOfferPricesRestApiToProductStorageClientInterface
{
    /**
     * @param string $mappingType
     * @param array<string> $identifiers
     * @param string $localeName
     *
     * @return array
     */
    public function getBulkProductConcreteStorageDataByMapping(
        string $mappingType,
        array $identifiers,
        string $localeName
    ): array;
}
