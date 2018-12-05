<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface;
use Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductOptionStorage\Price\ValuePriceReaderInterface;
use Spryker\Client\ProductOptionStorage\ProductOptionStorageConfig;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductOptionStorage\ProductOptionStorageConfig as SharedProductOptionStorageConfig;

class ProductOptionStorageReader implements ProductOptionStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

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
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductOptionStorage\Price\ValuePriceReaderInterface $valuePriceReader
     */
    public function __construct(
        ProductOptionStorageToStorageInterface $storageClient,
        Store $store,
        ProductOptionStorageToSynchronizationServiceInterface $synchronizationService,
        ValuePriceReaderInterface $valuePriceReader
    ) {
        $this->storageClient = $storageClient;
        $this->store = $store;
        $this->synchronizationService = $synchronizationService;
        $this->valuePriceReader = $valuePriceReader;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    public function getProductOptions($idProductAbstract, $locale)
    {
        $productAbstractOptionStorageData = $this->findStorageData($idProductAbstract);

        if (!$productAbstractOptionStorageData) {
            return null;
        }

        return $this->mapToProductAbstractOptionStorageTransfer($productAbstractOptionStorageData);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    public function getProductOptionsForCurrentStore($idProductAbstract)
    {
        $productAbstractOptionStorageData = $this->findStorageData($idProductAbstract);

        if (!$productAbstractOptionStorageData) {
            return null;
        }

        return $this->mapToProductAbstractOptionStorageTransfer($productAbstractOptionStorageData);
    }

    /**
     * @param int $idProductAbstract
     * @param string|null $locale
     *
     * @return array|null
     */
    protected function findStorageData(int $idProductAbstract, ?string $locale = null): ?array
    {
        if (ProductOptionStorageConfig::isCollectorCompatibilityMode()) {
            if ($locale === null) {
                $locale = Store::getInstance()->getCurrentLocale();
            }
            $clientLocatorName = Locator::class;
            /** @var \Spryker\Client\ProductOption\ProductOptionClientInterface $productOptionClient */
            $productOptionClient = $clientLocatorName::getInstance()->productOption()->client();

            $collectorData = $productOptionClient->getProductOptions($idProductAbstract, $locale);

            $formattedCollectorData = [
                'id_product_abstract' => $idProductAbstract,
                'product_option_groups' => [],
            ];

            foreach ($collectorData->getProductOptionGroups() as $productOptionGroupTransfer) {
                $productOptionData = $productOptionGroupTransfer->toArray();
                $productOptionData['product_option_values'] = $productOptionData['values'];
                unset($productOptionData['values']);

                $formattedCollectorData['product_option_groups'][] = $productOptionData;
            }

            return $formattedCollectorData;
        }

        $key = $this->generateKey($idProductAbstract);

        return $this->storageClient->get($key);
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
     *
     * @return string
     */
    protected function generateKey($idProductAbstract)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setStore($this->store->getStoreName())
            ->setReference($idProductAbstract);

        return $this->synchronizationService
            ->getStorageKeyBuilder(SharedProductOptionStorageConfig::PRODUCT_ABSTRACT_OPTION_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
