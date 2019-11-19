<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage\Dependency\Client;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ConfigurableBundleStorageToProductStorageClientInterface
{
    /**
     * @param string $mappingType
     * @param string $identifier
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMappingForCurrentLocale(string $mappingType, string $identifier): ?array;

    /**
     * @param array $productStorageData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductStorageDataToProductConcreteTransfer(array $productStorageData): ProductConcreteTransfer;
}
