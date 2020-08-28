<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Reader;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig;
use Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReaderInterface;

class ProductConfigurationInstanceReader implements ProductConfigurationInstanceReaderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReaderInterface
     */
    protected $configurationStorageReader;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface
     */
    protected $productConfigurationStorageMapper;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReaderInterface $configurationStorageReader
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface $sessionClient
     * @param \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface $productConfigurationStorageMapper
     */
    public function __construct(
        ProductConfigurationStorageReaderInterface $configurationStorageReader,
        ProductConfigurationStorageToSessionClientInterface $sessionClient,
        ProductConfigurationInstanceMapperInterface $productConfigurationStorageMapper
    ) {
        $this->configurationStorageReader = $configurationStorageReader;
        $this->sessionClient = $sessionClient;
        $this->productConfigurationStorageMapper = $productConfigurationStorageMapper;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(string $sku): ?ProductConfigurationInstanceTransfer
    {
        $productConfigurationInstanceTransfer = $this->sessionClient
            ->get(sprintf('%s:%s', ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION, $sku));

        if ($productConfigurationInstanceTransfer) {
            return $productConfigurationInstanceTransfer;
        }

        return $this->findProductConfigurationInstanceInStorage($sku);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    protected function findProductConfigurationInstanceInStorage(string $sku): ?ProductConfigurationInstanceTransfer
    {
        $productConfigurationStorageTransfer = $this->configurationStorageReader
            ->findProductConfigurationStorageBySku($sku);

        if (!$productConfigurationStorageTransfer) {
            return null;
        }

        return $this->productConfigurationStorageMapper
            ->mapProductConfigurationStorageTransferToProductConfigurationInstanceTransfer(
                $productConfigurationStorageTransfer,
                new ProductConfigurationInstanceTransfer()
            );
    }
}
