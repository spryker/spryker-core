<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Dependency\Client;

interface ProductLabelsRestApiToProductStorageClientInterface
{
    /**
     * @param string $mappingType
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array;

    /**
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return array
     */
    public function getProductConcreteStorageDataByMappingAndIdentifiers(
        string $mappingType,
        array $identifiers,
        string $localeName
    ): array;
}
