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
    protected const KEY_ID = 'id';

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
     * @var \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface
     */
    protected $productAbstractVariantsRestrictionFilter;

    /**
     * @var array
     */
    protected static $productsAbstractDataCache = [];

    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface $productAbstractVariantsRestrictionFilter
     * @param \Spryker\Client\ProductStorage\ProductStorageConfig $config
     * @param \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionPluginInterface[] $productAbstractRestrictionPlugins
     */
    public function __construct(
        ProductStorageToStorageClientInterface $storageClient,
        ProductStorageToSynchronizationServiceInterface $synchronizationService,
        Store $store,
        ProductAbstractAttributeMapRestrictionFilterInterface $productAbstractVariantsRestrictionFilter,
        ProductStorageConfig $config,
        array $productAbstractRestrictionPlugins = []
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
        $this->productAbstractVariantsRestrictionFilter = $productAbstractVariantsRestrictionFilter;
        $this->productAbstractRestrictionPlugins = $productAbstractRestrictionPlugins;
        $this->config = $config;
    }

    /**
     * @deprecated Use findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
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

        return $this->findStorageDataByMappingKeyAndLocaleName($mappingKey, $localeName);
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
     * @param string $mappingKey
     * @param string $localeName
     *
     * @return array|null
     */
    protected function findStorageDataByMappingKeyAndLocaleName(string $mappingKey, string $localeName)
    {
        $storageData = $this->storageClient->get($mappingKey);

        if ($this->config->isSendingToQueue()) {
            return $this->resolveMappingData($storageData, $localeName);
        }

        return $storageData;
    }

    /**
     * @param array $mappingData
     * @param string $localeName
     *
     * @return array|null
     */
    protected function resolveMappingData(array $mappingData, string $localeName): ?array
    {
        if (!$mappingData || !isset($mappingData[static::KEY_ID])) {
            return null;
        }

        return $this->findProductAbstractStorageData($mappingData[static::KEY_ID], $localeName);
    }
}
