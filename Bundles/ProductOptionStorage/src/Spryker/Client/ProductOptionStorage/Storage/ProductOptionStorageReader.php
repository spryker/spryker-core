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
     * @var \Spryker\Client\ProductOptionStorage\Price\ValuePriceReaderInterface
     */
    protected $valuePriceReader;

    /**
     * @param \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface $storageClient
     * @param \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductOptionStorage\Price\ValuePriceReaderInterface $valuePriceReader
     */
    public function __construct(
        ProductOptionStorageToStorageInterface $storageClient,
        ProductOptionStorageToSynchronizationServiceInterface $synchronizationService,
        ValuePriceReaderInterface $valuePriceReader
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->valuePriceReader = $valuePriceReader;
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
     * @param array $productAbstractOptionStorageData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    protected function mapToProductAbstractOptionStorageTransfer(array $productAbstractOptionStorageData)
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
            ->setLocale($locale)
            ->setReference($idProductAbstract);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductOptionStorageConfig::PRODUCT_ABSTRACT_OPTION_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
