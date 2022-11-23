<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Dependency\Client;

use Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceCriteriaTransfer;

interface ProductConfigurationShoppingListToProductConfigurationStorageClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceCriteriaTransfer $productConfigurationInstanceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer
     */
    public function getProductConfigurationInstanceCollection(
        ProductConfigurationInstanceCriteriaTransfer $productConfigurationInstanceCriteriaTransfer
    ): ProductConfigurationInstanceCollectionTransfer;
}
