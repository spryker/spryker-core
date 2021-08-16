<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Reader;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToLocaleClientInterface;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface;

class ProductConfigurationPriceReader implements ProductConfigurationPriceReaderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface
     */
    protected $productConfigurationInstanceReader;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToLocaleClientInterface $localeClient
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface $productConfigurationInstanceReader
     */
    public function __construct(
        ProductConfigurationStorageToLocaleClientInterface $localeClient,
        ProductConfigurationStorageToProductStorageClientInterface $productStorageClient,
        ProductConfigurationInstanceReaderInterface $productConfigurationInstanceReader
    ) {
        $this->localeClient = $localeClient;
        $this->productStorageClient = $productStorageClient;
        $this->productConfigurationInstanceReader = $productConfigurationInstanceReader;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesByIdProductConcrete(int $idProductConcrete): array
    {
        $productConcreteStorageData = $this->productStorageClient
            ->findProductConcreteStorageData($idProductConcrete, $this->localeClient->getCurrentLocale());

        if (!$productConcreteStorageData) {
            return [];
        }

        $productConcreteTransfer = $this->mapProductStorageDataToProductConcreteTransfer($productConcreteStorageData);
        $productConcreteTransfer->requireSku();

        $productConfigurationInstance = $this->productConfigurationInstanceReader->findProductConfigurationInstanceBySku(
            $productConcreteTransfer->getSkuOrFail()
        );

        if (!$productConfigurationInstance) {
            return [];
        }

        return $productConfigurationInstance->getPrices()->getArrayCopy();
    }

    /**
     * @param array $productConcreteStorageData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function mapProductStorageDataToProductConcreteTransfer(array $productConcreteStorageData): ProductConcreteTransfer
    {
        return (new ProductConcreteTransfer())->fromArray($productConcreteStorageData, true);
    }
}
