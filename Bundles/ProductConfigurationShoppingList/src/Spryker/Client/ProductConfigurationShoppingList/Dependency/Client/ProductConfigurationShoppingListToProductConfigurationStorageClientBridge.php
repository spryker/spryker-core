<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList\Dependency\Client;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

class ProductConfigurationShoppingListToProductConfigurationStorageClientBridge implements ProductConfigurationShoppingListToProductConfigurationStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface $productConfigurationStorageClient
     */
    public function __construct($productConfigurationStorageClient)
    {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(string $sku): ?ProductConfigurationInstanceTransfer
    {
        return $this->productConfigurationStorageClient->findProductConfigurationInstanceBySku($sku);
    }
}
