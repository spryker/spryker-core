<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationPersistentCart\Dependency\Client;

interface ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface
{
    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer[]
     */
    public function findProductConfigurationInstancesIndexedBySku(array $skus): array;
}
