<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\ProductCategoryStorage\Dependency\Client\ProductCategoryStorageToStorageClientInterface;
use Spryker\Client\ProductCategoryStorage\Dependency\Service\ProductCategoryStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductCategoryStorage\ProductCategoryStorageConfig;
use Spryker\Shared\ProductCategoryStorage\ProductCategoryStorageConfig as SharedProductCategoryStorageConfig;

class ProductAbstractCategoryStorageReader implements ProductAbstractCategoryStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductCategoryStorage\Dependency\Client\ProductCategoryStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductCategoryStorage\Dependency\Service\ProductCategoryStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductCategoryStorage\Dependency\Client\ProductCategoryStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductCategoryStorage\Dependency\Service\ProductCategoryStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(ProductCategoryStorageToStorageClientInterface $storageClient, ProductCategoryStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductAbstractCategory($idProductAbstract, $locale)
    {
        $productAbstractCategoryStorageData = $this->findStorageData($idProductAbstract, $locale);

        if (!$productAbstractCategoryStorageData) {
            return null;
        }

        $spyProductCategoryAbstractTransfer = new ProductAbstractCategoryStorageTransfer();

        return $spyProductCategoryAbstractTransfer->fromArray($productAbstractCategoryStorageData, true);
    }

    /**
     * @param array $productAbstractIds
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[]|null
     */
    public function findBulkProductAbstractCategory(array $productAbstractIds, $locale): ?array
    {
        $productAbstractCategoryStorageData = $this->findBulkStorageData($productAbstractIds, $locale);

        if (!$productAbstractCategoryStorageData) {
            return null;
        }

        $response = [];
        foreach ($productAbstractCategoryStorageData as $key => $item) {
            $spyProductCategoryAbstractTransfer = new ProductAbstractCategoryStorageTransfer();
            $spyProductCategoryAbstractTransfer->fromArray($item, true);
            $response[$key] = $spyProductCategoryAbstractTransfer;
        }

        return $response;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return array|null
     */
    protected function findStorageData(int $idProductAbstract, string $locale): ?array
    {
        if (ProductCategoryStorageConfig::isCollectorCompatibilityMode()) {
            $clientLocatorClassName = Locator::class;
            /** @var \Spryker\Client\Product\ProductClientInterface $productClient */
            $productClient = $clientLocatorClassName::getInstance()->product()->client();
            $collectorData = $productClient->getProductAbstractFromStorageById($idProductAbstract, $locale);
            $categories = [];

            foreach ($collectorData['categories'] as $category) {
                $categories[] = [
                    'category_node_id' => $category['nodeId'],
                    'name' => $category['name'],
                    'url' => $category['url'],
                ];
            }

            return [
                'id_product_abstract' => $idProductAbstract,
                'categories' => $categories,
            ];
        }

        $key = $this->generateKey($idProductAbstract, $locale);
        $productAbstractCategoryStorageData = $this->storageClient->get($key);

        return $productAbstractCategoryStorageData;
    }

    /**
     * @param array $productAbstractIds
     * @param string $locale
     *
     * @return array|null
     */
    protected function findBulkStorageData(array $productAbstractIds, string $locale): ?array
    {
        $keys = [];
        $mapKeySku = [];
        foreach ($productAbstractIds as $sku => $productAbstractId) {
            $generatedKey = $this->generateKey($productAbstractId, $locale);

            $keys[$sku] = $generatedKey;
            $mapKeySku['kv:' . $generatedKey] = $sku;
        }

        $productAbstractCategoryStorageData = $this->storageClient->getMulti($keys);

        $response = [];
        foreach ($productAbstractCategoryStorageData as $key => $item) {
            $response[$mapKeySku[$key]] = json_decode($item, true);
        }

        return $response;
    }

    /**
     * @param int|string $idProductAbstract
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
            ->getStorageKeyBuilder(SharedProductCategoryStorageConfig::PRODUCT_ABSTRACT_CATEGORY_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
