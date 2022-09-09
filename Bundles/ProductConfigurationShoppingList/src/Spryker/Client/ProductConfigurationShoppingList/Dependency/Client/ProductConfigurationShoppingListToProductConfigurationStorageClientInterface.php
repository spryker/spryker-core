<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Dependency\Client;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

interface ProductConfigurationShoppingListToProductConfigurationStorageClientInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(string $sku): ?ProductConfigurationInstanceTransfer;
}
