<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart\Dependency\Client;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

interface ProductConfigurationCartToProductConfigurationStorageClientInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(
        string $sku
    ): ?ProductConfigurationInstanceTransfer;

    /**
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConfigurationInstanceTransfer>
     */
    public function findProductConfigurationInstancesIndexedBySku(array $skus): array;
}
