<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface;
use Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductOptionStorage\Price\ValuePriceReaderInterface;
use Spryker\Shared\ProductOptionStorage\ProductOptionStorageConfig;
use Spryker\Shared\Kernel\Store;

class ProductOptionStorageReader implements ProductOptionStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var ValuePriceReaderInterface
     */
    protected $valuePriceReader;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @param \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface $storageClient
     * @param \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceInterface $synchronizationService
     * @param ValuePriceReaderInterface $valuePriceReader
     * @param Store $store
     */
    public function __construct(
        ProductOptionStorageToStorageInterface $storageClient,
        ProductOptionStorageToSynchronizationServiceInterface $synchronizationService,
        ValuePriceReaderInterface $valuePriceReader,
        Store $store
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->valuePriceReader = $valuePriceReader;
        $this->store = $store;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    public function getProductOptions($idProductAbstract, $locale)
    {
        $key = $this->generateKey($idProductAbstract, $locale);
        $productAbstractOptionStorageData = $this->storageClient->get($key);

        if (!$productAbstractOptionStorageData) {
            return null;
        }

        return $this->mapToProductAbstractOptionStorageTransfer($productAbstractOptionStorageData);
    }

    /**
     * @param $productAbstractOptionStorageData
     *
     * @return ProductAbstractOptionStorageTransfer
     */
    protected function mapToProductAbstractOptionStorageTransfer($productAbstractOptionStorageData)
    {
        $productAbstractOptionStorageTransfer = new ProductAbstractOptionStorageTransfer();
        $productAbstractOptionStorageTransfer->fromArray($productAbstractOptionStorageData, true);

        foreach ($productAbstractOptionStorageTransfer->getProductOptionGroups() as $productOptionGroup) {
            $this->valuePriceReader->resolvePrices($productOptionGroup);
        }

        return $productAbstractOptionStorageTransfer;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return string
     */
    protected function generateKey($idProductAbstract, $locale)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setStore($this->store->getStoreName())
            ->setLocale($locale)
            ->setReference($idProductAbstract);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductOptionStorageConfig::PRODUCT_ABSTRACT_OPTION_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

}
