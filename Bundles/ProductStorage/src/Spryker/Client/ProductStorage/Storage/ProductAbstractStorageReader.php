<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface;
use Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductStorage\Exception\NotFoundProductAbstractDataCacheException;
use Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface;
use Spryker\Client\ProductStorage\ProductStorageConfig;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductStorage\ProductStorageConstants;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToUnderscore;

class ProductAbstractStorageReader implements ProductAbstractStorageReaderInterface
{
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const KEY_PRICES = 'prices';
    protected const KEY_CATEGORIES = 'categories';
    protected const KEY_IMAGE_SETS = 'imageSets';
    protected const KEY_ATTRIBUTE_MAP = 'attribute_map';

    /**
     * @var \Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionPluginInterface[]
     */
    protected $productAbstractRestrictionPlugins;

    /**
     * @var \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionFilterPluginInterface[]
     */
    protected $productAbstractRestrictionFilterPlugins;

    /**
     * @var \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface
     */
    protected $productAbstractVariantsRestrictionFilter;

    /**
     * @var array
     */
    protected static $productsAbstractDataCache = [];

    /**
     * @param \Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface $productAbstractVariantsRestrictionFilter
     * @param \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionPluginInterface[] $productAbstractRestrictionPlugins
     * @param \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionFilterPluginInterface[] $productAbstractRestrictionFilterPlugins
     */
    public function __construct(
        ProductStorageToStorageClientInterface $storageClient,
        ProductStorageToSynchronizationServiceInterface $synchronizationService,
        Store $store,
        ProductAbstractAttributeMapRestrictionFilterInterface $productAbstractVariantsRestrictionFilter,
        array $productAbstractRestrictionPlugins = [],
        array $productAbstractRestrictionFilterPlugins = []
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
        $this->productAbstractVariantsRestrictionFilter = $productAbstractVariantsRestrictionFilter;
        $this->productAbstractRestrictionPlugins = $productAbstractRestrictionPlugins;
        $this->productAbstractRestrictionFilterPlugins = $productAbstractRestrictionFilterPlugins;
    }

    /**
     * @deprecated Use `\Spryker\Client\ProductStorage\Storage\ProductAbstractStorageReader::findProductAbstractStorageData()` instead.
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    public function getProductAbstractStorageData($idProductAbstract, $localeName)
    {
        return $this->findProductAbstractStorageData($idProductAbstract, $localeName);
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
    {
        if ($this->hasProductAbstractDataCacheByIdProductAbstractAndLocaleName($idProductAbstract, $localeName)) {
            return $this->getProductAbstractDataCacheByIdProductAbstractAndLocaleName($idProductAbstract, $localeName);
        }

        $productStorageData = $this->findStorageData($idProductAbstract, $localeName);
        $this->cacheProductAbstractDataByIdProductAbstractAndLocaleName($idProductAbstract, $localeName, $productStorageData);

        return $productStorageData;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array|null
     */
    protected function findStorageData(int $idProductAbstract, string $localeName): ?array
    {
        if ($this->isProductAbstractRestricted($idProductAbstract)) {
            return null;
        }

        if (ProductStorageConfig::isCollectorCompatibilityMode()) {
            $clientLocatorClassName = Locator::class;
            /** @var \Spryker\Client\Product\ProductClientInterface $productClient */
            $productClient = $clientLocatorClassName::getInstance()->product()->client();
            $collectorData = $productClient->getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract);

            unset($collectorData['prices'], $collectorData['categories'], $collectorData['imageSets']);
            $collectorData = $this->changeKeys($collectorData);

            $attributeMap = $productClient->getAttributeMapByIdAndLocale($idProductAbstract, $localeName);
            $attributeMap = $this->changeKeys($attributeMap);

            $collectorData['attribute_map'] = $attributeMap;

            return $collectorData;
        }

        $key = $this->getStorageKey((string)$idProductAbstract, $localeName);

        $productStorageData = $this->storageClient->get($key);

        if (!$productStorageData) {
            return null;
        }

        $productStorageData = $this->productAbstractVariantsRestrictionFilter
            ->filterAbstractProductVariantsData($productStorageData);

        return $productStorageData;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function changeKeys(array $data): array
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToUnderscore())
            ->attach(new StringToLower());

        $filteredData = [];

        foreach ($data as $key => $value) {
            $filteredData[$filterChain->filter($key)] = $value;
        }

        return $filteredData;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool
    {
        foreach ($this->productAbstractRestrictionPlugins as $productAbstractRestrictionPlugin) {
            if ($productAbstractRestrictionPlugin->isRestricted($idProductAbstract)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $mappingType
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array
    {
        $reference = $mappingType . ':' . $identifier;
        $mappingKey = $this->getStorageKey($reference, $localeName);
        $mappingData = $this->storageClient->get($mappingKey);

        if (!$mappingData) {
            return null;
        }

        return $this->findProductAbstractStorageData($mappingData['id'], $localeName);
    }

    /**
     * @param string $reference
     * @param string $locale
     *
     * @return string
     */
    protected function getStorageKey(string $reference, string $locale): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference($reference)
            ->setLocale($locale)
            ->setStore($this->store->getStoreName());

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @throws \Spryker\Client\ProductStorage\Exception\NotFoundProductAbstractDataCacheException
     *
     * @return array
     */
    protected function getProductAbstractDataCacheByIdProductAbstractAndLocaleName(int $idProductAbstract, string $localeName): array
    {
        if (!$this->hasProductAbstractDataCacheByIdProductAbstractAndLocaleName($idProductAbstract, $localeName)) {
            throw new NotFoundProductAbstractDataCacheException();
        }

        return static::$productsAbstractDataCache[$idProductAbstract][$localeName];
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return bool
     */
    protected function hasProductAbstractDataCacheByIdProductAbstractAndLocaleName(int $idProductAbstract, string $localeName): bool
    {
        return isset(static::$productsAbstractDataCache[$idProductAbstract][$localeName]);
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param array|null $productData
     *
     * @return void
     */
    protected function cacheProductAbstractDataByIdProductAbstractAndLocaleName(int $idProductAbstract, string $localeName, ?array $productData): void
    {
        static::$productsAbstractDataCache[$idProductAbstract][$localeName] = $productData;
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return array
     */
    protected function getProductAbstractDataCacheByProductAbstractIdsAndLocaleName(array $productAbstractIds, string $localeName): array
    {
        $cachedProductAbstractData = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            if ($this->hasProductAbstractDataCacheByIdProductAbstractAndLocaleName($idProductAbstract, $localeName)) {
                $cachedProductAbstractData[$idProductAbstract] = $this->getProductAbstractDataCacheByIdProductAbstractAndLocaleName($idProductAbstract, $localeName);
            }
        }

        return $cachedProductAbstractData;
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return array
     */
    public function getBulkProductAbstractStorageDataByProductAbstractIdsAndLocaleName(array $productAbstractIds, string $localeName): array
    {
        $cachedProductAbstractStorageData = $this->getProductAbstractDataCacheByProductAbstractIdsAndLocaleName($productAbstractIds, $localeName);

        $productAbstractIds = array_diff($productAbstractIds, array_keys($cachedProductAbstractStorageData));
        $productAbstractIds = $this->filterRestrictedProductAbstractIds($productAbstractIds);
        if (!$productAbstractIds) {
            return $cachedProductAbstractStorageData;
        }

        $productAbstractStorageData = $this->getBulkProductAbstractStorageData($productAbstractIds, $localeName);

        return array_merge($cachedProductAbstractStorageData, $productAbstractStorageData);
    }

    /**
     * @param array $productAbstractIds
     * @param string $localeName
     *
     * @return array
     */
    protected function getBulkProductAbstractStorageData(array $productAbstractIds, string $localeName): array
    {
        if (ProductStorageConfig::isCollectorCompatibilityMode()) {
            return $this->getBulkProductAbstractStorageDataForCollectorCompatibilityMode($productAbstractIds, $localeName);
        }

        $productStorageDataCollection = $this->storageClient->getMulti($this->generateStorageKeys($productAbstractIds, $localeName));

        return $this->mapBulkProductStorageData($productStorageDataCollection, $localeName);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return string[]
     */
    protected function generateStorageKeys(array $productAbstractIds, string $localeName): array
    {
        $storageKeys = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $storageKeys[] = $this->getStorageKey((string)$idProductAbstract, $localeName);
        }

        return $storageKeys;
    }

    /**
     * @param array $productStorageDataCollection
     * @param string $localeName
     *
     * @return array
     */
    protected function mapBulkProductStorageData(array $productStorageDataCollection, string $localeName): array
    {
        $productAbstractStorageData = [];
        foreach ($productStorageDataCollection as $productStorageData) {
            $productStorageData = json_decode($productStorageData, true);
            $filteredProductData = $this->productAbstractVariantsRestrictionFilter
                ->filterAbstractProductVariantsData($productStorageData);
            $idProductAbstract = $filteredProductData[static::KEY_ID_PRODUCT_ABSTRACT];
            $productAbstractStorageData[$idProductAbstract] = $filteredProductData;

            $this->cacheProductAbstractDataByIdProductAbstractAndLocaleName($idProductAbstract, $localeName, $filteredProductData);
        }

        return $productAbstractStorageData;
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return array
     */
    protected function getBulkProductAbstractStorageDataForCollectorCompatibilityMode(array $productAbstractIds, string $localeName): array
    {
        $clientLocatorClassName = Locator::class;
        /** @var \Spryker\Client\Product\ProductClientInterface $productClient */
        $productClient = $clientLocatorClassName::getInstance()->product()->client();

        $collectorData = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $productAbstractData = $productClient->getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract);

            unset($productAbstractData[static::KEY_PRICES], $productAbstractData[static::KEY_CATEGORIES], $productAbstractData[static::KEY_IMAGE_SETS]);
            $productAbstractData = $this->changeKeys($productAbstractData);

            $attributeMap = $productClient->getAttributeMapByIdAndLocale($idProductAbstract, $localeName);
            $attributeMap = $this->changeKeys($attributeMap);

            $productAbstractData[static::KEY_ATTRIBUTE_MAP] = $attributeMap;

            $collectorData[$productAbstractData[static::KEY_ID_PRODUCT_ABSTRACT]] = $productAbstractData;
        }

        return $collectorData;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    protected function filterRestrictedProductAbstractIds(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        //This was added for BC reason (if no bulk plugins was added)
        if (!$this->productAbstractRestrictionFilterPlugins) {
            $filteredIds = [];
            foreach ($productAbstractIds as $idProductAbstract) {
                if (!$this->isProductAbstractRestricted($idProductAbstract)) {
                    $filteredIds[] = $idProductAbstract;
                }
            }

            return $filteredIds;
        }

        foreach ($this->productAbstractRestrictionFilterPlugins as $productAbstractRestrictionFilterPlugin) {
            $productAbstractIds = $productAbstractRestrictionFilterPlugin->filter($productAbstractIds);
        }

        return $productAbstractIds;
    }
}
