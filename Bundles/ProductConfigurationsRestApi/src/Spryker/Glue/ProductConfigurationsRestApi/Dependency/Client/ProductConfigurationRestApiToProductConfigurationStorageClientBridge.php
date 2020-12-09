<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

class ProductConfigurationRestApiToProductConfigurationStorageClientBridge implements ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
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
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(string $concreteSku): ?ProductConfigurationInstanceTransfer
    {
        return $this->productConfigurationStorageClient->findProductConfigurationInstanceBySku($concreteSku);
    }
}
