<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Reader;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapperInterface;
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
     * @var \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapperInterface
     */
    protected $productConfigurationStorageMapper;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReaderInterface $configurationStorageReader
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface $sessionClient
     * @param \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationStorageMapperInterface $productConfigurationStorageMapper
     */
    public function __construct(
        ProductConfigurationStorageReaderInterface $configurationStorageReader,
        ProductConfigurationStorageToSessionClientInterface $sessionClient,
        ProductConfigurationStorageMapperInterface $productConfigurationStorageMapper
    ) {

        $this->configurationStorageReader = $configurationStorageReader;
        $this->sessionClient = $sessionClient;
        $this->productConfigurationStorageMapper = $productConfigurationStorageMapper;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(
        string $sku
    ): ?ProductConfigurationInstanceTransfer {
        $hasProductConfigurationInSession = $this->sessionClient
            ->has(ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION);

        if ($hasProductConfigurationInSession) {
            return $this->sessionClient->get(ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION);
        }

         return $this->findProductConfigurationInstanceFomStorage($sku);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    protected function findProductConfigurationInstanceFomStorage(string $sku): ?ProductConfigurationInstanceTransfer
    {
        $productConfigurationStorage = $this->configurationStorageReader->findProductConfigurationStorageBySku($sku);

        if (!$productConfigurationStorage) {
            return null;
        }

        return $this->productConfigurationStorageMapper
            ->mapProductConfigurationStorageTransferToProductConfigurationInstanceTransfer(
                $productConfigurationStorage,
                new ProductConfigurationInstanceTransfer()
            );
    }
}
