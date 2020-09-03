<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Reader;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToLocaleClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig;
use Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReaderInterface;

class ProductConfigurationInstanceReader implements ProductConfigurationInstanceReaderInterface
{
    protected const PRODUCT_DATA_SKU_KEY = 'sku';

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
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Storage\ProductConfigurationStorageReaderInterface $configurationStorageReader
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientInterface $sessionClient
     * @param \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface $productConfigurationStorageMapper
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToLocaleClientInterface $localeClient
     */
    public function __construct(
        ProductConfigurationStorageReaderInterface $configurationStorageReader,
        ProductConfigurationStorageToSessionClientInterface $sessionClient,
        ProductConfigurationInstanceMapperInterface $productConfigurationStorageMapper,
        ProductConfigurationStorageToProductStorageClientInterface $productStorageClient,
        ProductConfigurationStorageToLocaleClientInterface $localeClient
    ) {
        $this->configurationStorageReader = $configurationStorageReader;
        $this->sessionClient = $sessionClient;
        $this->productConfigurationStorageMapper = $productConfigurationStorageMapper;
        $this->productStorageClient = $productStorageClient;
        $this->localeClient = $localeClient;
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
     * @param string $groupKey
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceByGroupKey(
        string $groupKey,
        QuoteTransfer $quoteTransfer
    ): ?ProductConfigurationInstanceTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getGroupKey() === $groupKey) {
                return $this->findProductConfigurationInstanceBySku($itemTransfer->getSku());
            }
        }

        return null;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesByIdProductConcrete(int $idProductConcrete): array
    {
        $productData = $this->productStorageClient
            ->findProductConcreteStorageData($idProductConcrete, $this->localeClient->getCurrentLocale());

        $productConfigurationInstance = $this->findProductConfigurationInstanceBySku(
            $productData[static::PRODUCT_DATA_SKU_KEY]
        );

        if (!$productConfigurationInstance) {
            return [];
        }

        return $productConfigurationInstance->getPrices()->getArrayCopy();
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
