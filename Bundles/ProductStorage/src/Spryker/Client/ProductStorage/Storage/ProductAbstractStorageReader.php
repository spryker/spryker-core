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
    protected const KEY_ID = 'id';

    /**
     * @uses \Spryker\Zed\Storage\Communication\Table\StorageTable::KV_PREFIX
     */
    protected const KV_PREFIX = 'kv:';

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
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return int[]
     */
    public function getBulkProductAbstractIdsByMapping(string $mappingType, array $identifiers, string $localeName): array
    {
        return $this->getProductAbstractIdsByMapping($mappingType, $identifiers, $localeName);
    }

    /**
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return int[]
     */
    protected function getProductAbstractIdsByMapping(string $mappingType, array $identifiers, string $localeName): array
    {
        $storageKeys = $this->getStorageKeysByMapping($mappingType, $identifiers, $localeName);
        $mappingData = $this->storageClient->getMulti($storageKeys);
        $mappingData = array_filter($mappingData);

        if (count($mappingData) === 0) {
            return [];
        }

        $identifiersByStorageKey = $this->getIdentifiersIndexedByStorageKey($storageKeys);
        $productAbstractIds = [];
        foreach ($mappingData as $storageKey => $mappingDataItem) {
            $decodedMappingDataItem = json_decode($mappingDataItem, true);
            $productAbstractIds[$identifiersByStorageKey[$storageKey]] = $decodedMappingDataItem[static::KEY_ID] ?? null;
        }

        return $productAbstractIds;
    }

    /**
     * @param string[] $storageKeys
     *
     * @return string[]
     */
    protected function getIdentifiersIndexedByStorageKey(array $storageKeys): array
    {
        $identifiersByStorageKey = [];
        foreach ($storageKeys as $identifier => $storageKey) {
            $identifiersByStorageKey[static::KV_PREFIX . $storageKey] = $identifier;
        }

        return $identifiersByStorageKey;
    }

    /**
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return string[]
     */
    protected function getStorageKeysByMapping(string $mappingType, array $identifiers, string $localeName): array
    {
        $storageKeys = [];
        foreach ($identifiers as $identifier) {
            $storageKeys[$identifier] = $this->getStorageKey(
                sprintf('%s:%s', $mappingType, $identifier),
                $localeName
            );
        }

        return $storageKeys;
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
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return array
     */
    public function findBulkProductAbstractStorageDataByMapping(string $mappingType, array $identifiers, string $localeName): array
    {
        $productAbstractIds = $this->getProductAbstractIdsByMapping($mappingType, $identifiers, $localeName);

        return $this->getBulkProductAbstractStorageDataByProductAbstractIdsAndLocaleName($productAbstractIds, $localeName);
    }

    /**
     * @param string $reference
     * @param string $locale
     * @param string|null $storeName
     *
     * @return string
     */
    protected function getStorageKey(string $reference, string $locale, ?string $storeName = null): string
    {
        if ($storeName === null) {
            $storeName = APPLICATION_STORE;
        }
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference($reference)
            ->setLocale($locale)
            ->setStore($storeName);

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
     * @param string $storeName
     *
     * @throws \Spryker\Client\ProductStorage\Exception\NotFoundProductAbstractDataCacheException
     *
     * @return array
     */
    protected function getProductAbstractDataCacheByIdProductAbstractForLocaleNameAndStore(
        int $idProductAbstract,
        string $localeName,
        string $storeName
    ): array {
        if (!$this->hasProductAbstractDataCacheByIdProductAbstractForLocaleNameAndStore($idProductAbstract, $localeName, $storeName)) {
            throw new NotFoundProductAbstractDataCacheException();
        }

        return static::$productsAbstractDataCache[$idProductAbstract][$localeName][$storeName];
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
     * @param string $storeName
     *
     * @return bool
     */
    protected function hasProductAbstractDataCacheByIdProductAbstractForLocaleNameAndStore(
        int $idProductAbstract,
        string $localeName,
        string $storeName
    ): bool {
        return isset(static::$productsAbstractDataCache[$idProductAbstract][$localeName][$storeName]);
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
     * @param int $idProductAbstract
     * @param string $localeName
     * @param array|null $productData
     * @param string|null $storeName
     *
     * @return void
     */
    protected function cacheProductAbstractDataByIdProductAbstractForLocaleNameAndStore(
        int $idProductAbstract,
        string $localeName,
        ?array $productData,
        ?string $storeName = null
    ): void {
        if ($storeName === null) {
            $this->cacheProductAbstractDataByIdProductAbstractAndLocaleName(
                $idProductAbstract,
                $localeName,
                $productData
            );
        }

        static::$productsAbstractDataCache[$idProductAbstract][$localeName][$storeName] = $productData;
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
     * @param string $storeName
     *
     * @return array
     */
    protected function getProductAbstractDataCacheByProductAbstractIdsForLocaleNameAndStore(
        array $productAbstractIds,
        string $localeName,
        string $storeName
    ): array {
        $cachedProductAbstractData = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            if ($this->hasProductAbstractDataCacheByIdProductAbstractForLocaleNameAndStore($idProductAbstract, $localeName, $storeName)) {
                $cachedProductAbstractData[$idProductAbstract] = $this->getProductAbstractDataCacheByIdProductAbstractForLocaleNameAndStore($idProductAbstract, $localeName, $storeName);
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

        return $cachedProductAbstractStorageData + $productAbstractStorageData;
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStore(
        array $productAbstractIds,
        string $localeName,
        string $storeName
    ): array {
        $cachedProductAbstractStorageData = $this->getProductAbstractDataCacheByProductAbstractIdsForLocaleNameAndStore($productAbstractIds, $localeName, $storeName);

        $productAbstractIds = array_diff($productAbstractIds, array_keys($cachedProductAbstractStorageData));
        $productAbstractIds = $this->filterRestrictedProductAbstractIds($productAbstractIds);
        if (!$productAbstractIds) {
            return $cachedProductAbstractStorageData;
        }

        $productAbstractStorageData = $this->getBulkProductAbstractStorageData($productAbstractIds, $localeName, $storeName);

        return $cachedProductAbstractStorageData + $productAbstractStorageData;
    }

    /**
     * @param array $productAbstractIds
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return array
     */
    protected function getBulkProductAbstractStorageData(array $productAbstractIds, string $localeName, ?string $storeName = null): array
    {
        if (ProductStorageConfig::isCollectorCompatibilityMode()) {
            return $this->getBulkProductAbstractStorageDataForCollectorCompatibilityMode($productAbstractIds, $localeName);
        }

        $productStorageDataCollection = $this->storageClient->getMulti($this->generateStorageKeys($productAbstractIds, $localeName, $storeName));
        $productStorageDataCollection = array_filter($productStorageDataCollection);

        return $this->mapBulkProductStorageData($productStorageDataCollection, $localeName, $storeName);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return string[]
     */
    protected function generateStorageKeys(array $productAbstractIds, string $localeName, ?string $storeName = null): array
    {
        $storageKeys = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $storageKeys[] = $this->getStorageKey((string)$idProductAbstract, $localeName, $storeName);
        }

        return $storageKeys;
    }

    /**
     * @param array $productStorageDataCollection
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return array
     */
    protected function mapBulkProductStorageData(array $productStorageDataCollection, string $localeName, ?string $storeName = null): array
    {
        $productAbstractStorageData = [];
        foreach ($productStorageDataCollection as $productStorageData) {
            $productStorageData = json_decode($productStorageData, true);
            $filteredProductData = $this->productAbstractVariantsRestrictionFilter
                ->filterAbstractProductVariantsData($productStorageData);
            $idProductAbstract = $filteredProductData[static::KEY_ID_PRODUCT_ABSTRACT];
            $productAbstractStorageData[$idProductAbstract] = $filteredProductData;

            $this->cacheProductAbstractDataByIdProductAbstractForLocaleNameAndStore(
                $idProductAbstract,
                $localeName,
                $filteredProductData,
                $storeName
            );
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
