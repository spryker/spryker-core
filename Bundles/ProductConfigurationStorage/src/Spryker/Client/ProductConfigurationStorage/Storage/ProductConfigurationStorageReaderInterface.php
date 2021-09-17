<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Storage;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;

interface ProductConfigurationStorageReaderInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationStorageTransfer|null
     */
    public function findProductConfigurationStorageBySku(
        string $sku
    ): ?ProductConfigurationStorageTransfer;

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConfigurationStorageTransfer>
     */
    public function findProductConfigurationStoragesBySkus(array $skus): array;
}
