<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Writer;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig;

class ProductConfigurationInstanceWriter implements ProductConfigurationInstanceWriterInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface $sessionClient
     */
    public function __construct(
        ProductConfigurationStorageToSessionClientInterface $sessionClient
    ) {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return void
     */
    public function storeProductConfigurationInstanceBySku(
        string $sku,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): void {
        $this->sessionClient->set(
            sprintf('%s:%s', ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION, $sku),
            $productConfigurationInstanceTransfer
        );
    }
}
