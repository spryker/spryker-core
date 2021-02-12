<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Storage;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageConfig;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface;
use Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface;
use Spryker\Client\CategoryStorage\Exception\CategoryNodeDataCacheNotFoundException;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use Spryker\Shared\Kernel\Store;

class CategoryNodeStorage implements CategoryNodeStorageInterface
{
    /**
     * @var \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var array
     */
    protected static $categoryNodeDataCache = [];

    /**
     * @param \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface $storageClient
     * @param \Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(CategoryStorageToStorageInterface $storageClient, CategoryStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $idCategoryNode, string $localeName, string $storeName): CategoryNodeStorageTransfer
    {
        $categoryData = $this->getStorageData($idCategoryNode, $localeName, $storeName);

        $categoryNodeStorageTransfer = new CategoryNodeStorageTransfer();
        if ($categoryData) {
            $categoryNodeStorageTransfer->fromArray($categoryData, true);
        }

        return $categoryNodeStorageTransfer;
    }

    /**
     * @param int[] $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function getCategoryNodeByIds(array $categoryNodeIds, string $localeName, string $storeName): array
    {
        $cachedCategoryNodeStorageData = $this->getCategoryNodeDataCacheByIdCategoryNodesAndLocaleName(
            $categoryNodeIds,
            $localeName,
            $storeName
        );
        $categoryNodeIds = array_diff($categoryNodeIds, array_keys($cachedCategoryNodeStorageData));

        if (!$categoryNodeIds) {
            return $cachedCategoryNodeStorageData;
        }

        $categoryNodes = $this->getStorageDataByNodeIds($categoryNodeIds, $localeName, $storeName);

        $categoryNodeStorageTransfers = [];
        foreach ($categoryNodes as $categoryNode) {
            $categoryNodeStorageTransfers[] = $this->mapCategoryNodeStorageDataToCategoryNodeStorageTransfer(
                $categoryNode,
                $localeName,
                $storeName
            );
        }

        return array_merge($cachedCategoryNodeStorageData, $categoryNodeStorageTransfers);
    }

    /**
     * @param string $categoryNodeStorageData
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer|null
     */
    protected function mapCategoryNodeStorageDataToCategoryNodeStorageTransfer(
        string $categoryNodeStorageData,
        string $localeName,
        string $storeName
    ): ?CategoryNodeStorageTransfer {
        $decodedCategoryNodeStorageData = json_decode($categoryNodeStorageData, true);

        if (!$decodedCategoryNodeStorageData) {
            return null;
        }

        $categoryNodeStorageTransfer = (new CategoryNodeStorageTransfer())
            ->fromArray($decodedCategoryNodeStorageData, true);

        $this->cacheCategoryNodeDataByIdCategoryNodeAndLocaleName(
            $categoryNodeStorageTransfer->getNodeIdOrFail(),
            $localeName,
            $storeName,
            $categoryNodeStorageTransfer
        );

        return $categoryNodeStorageTransfer;
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return array|null
     */
    protected function getStorageData(int $idCategoryNode, string $localeName, string $storeName): ?array
    {
        if (CategoryStorageConfig::isCollectorCompatibilityMode()) {
            return $this->getCollectorStorageData($idCategoryNode, $localeName);
        }

        $categoryNodeKey = $this->generateKey($idCategoryNode, $localeName, $storeName);

        return $this->storageClient->get($categoryNodeKey);
    }

    /**
     * @param int[] $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return string[]
     */
    protected function getStorageDataByNodeIds(array $categoryNodeIds, string $localeName, string $storeName): array
    {
        $categoryNodeKeys = [];
        foreach ($categoryNodeIds as $categoryNodeId) {
            $categoryNodeKeys[] = $this->generateKey($categoryNodeId, $localeName, $storeName);
        }

        return $this->storageClient->getMulti($categoryNodeKeys);
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return array|null
     */
    protected function getCollectorStorageData(int $idCategoryNode, string $localeName): ?array
    {
        $categoryNodeKey = sprintf(
            '%s.%s.resource.categorynode.%s',
            strtolower(Store::getInstance()->getStoreName()),
            strtolower($localeName),
            $idCategoryNode
        );

        $collectorData = $this->storageClient->get($categoryNodeKey);

        if (!$collectorData) {
            return null;
        }

        if (empty($collectorData['parents'])) {
            unset($collectorData['parents']);
        }

        if (empty($collectorData['children'])) {
            unset($collectorData['children']);
        }

        if (isset($collectorData['parents']) && is_array($collectorData['parents'])) {
            $collectorData['parents'] = $this->filterCollectorDataRecursive($collectorData['parents']);
        }

        if (isset($collectorData['children']) && is_array($collectorData['children'])) {
            $collectorData['children'] = $this->filterCollectorDataRecursive($collectorData['children']);
        }

        return $collectorData;
    }

    /**
     * @param array $categories
     *
     * @return array
     */
    protected function filterCollectorDataRecursive(array $categories): array
    {
        $filteredCategories = [];
        foreach ($categories as $category) {
            if (empty($category['children'])) {
                unset($category['children']);
            }
            if (empty($category['parents'])) {
                unset($category['parents']);
            }
            if (isset($category['children'])) {
                $category['children'] = $this->filterCollectorDataRecursive($category['children']);
            }
            if (isset($category['parents'])) {
                $category['parents'] = $this->filterCollectorDataRecursive($category['parents']);
            }
            $filteredCategories[] = $category;
        }

        return $filteredCategories;
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey(int $idCategoryNode, string $localeName, string $storeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setReference((string)$idCategoryNode)
            ->setLocale($localeName)
            ->setStore($storeName);

        return $this->synchronizationService
            ->getStorageKeyBuilder(CategoryStorageConstants::CATEGORY_NODE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer|null $categoryNodeStorageTransfer
     *
     * @return void
     */
    protected function cacheCategoryNodeDataByIdCategoryNodeAndLocaleName(
        int $idCategoryNode,
        string $localeName,
        string $storeName,
        ?CategoryNodeStorageTransfer $categoryNodeStorageTransfer
    ): void {
        static::$categoryNodeDataCache[$idCategoryNode][$localeName][$storeName] = $categoryNodeStorageTransfer;
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @throws \Spryker\Client\CategoryStorage\Exception\CategoryNodeDataCacheNotFoundException
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    protected function getCategoryNodeDataCacheByIdCategoryNodeAndLocaleNameAndStoreName(
        int $idCategoryNode,
        string $localeName,
        string $storeName
    ): CategoryNodeStorageTransfer {
        if (!$this->hasCategoryNodeDataCacheByIdCategoryNodeAndLocaleNameAndStoreName($idCategoryNode, $localeName, $storeName)) {
            throw new CategoryNodeDataCacheNotFoundException();
        }

        return static::$categoryNodeDataCache[$idCategoryNode][$localeName][$storeName];
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return bool
     */
    protected function hasCategoryNodeDataCacheByIdCategoryNodeAndLocaleNameAndStoreName(int $idCategoryNode, string $localeName, string $storeName): bool
    {
        return isset(static::$categoryNodeDataCache[$idCategoryNode][$localeName][$storeName]);
    }

    /**
     * @param int[] $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    protected function getCategoryNodeDataCacheByIdCategoryNodesAndLocaleName(array $categoryNodeIds, string $localeName, string $storeName): array
    {
        $cachedCategoryNodeData = [];
        foreach ($categoryNodeIds as $idCategoryNode) {
            if ($this->hasCategoryNodeDataCacheByIdCategoryNodeAndLocaleNameAndStoreName($idCategoryNode, $localeName, $storeName)) {
                $cachedCategoryNodeData[$idCategoryNode] = $this->getCategoryNodeDataCacheByIdCategoryNodeAndLocaleNameAndStoreName(
                    $idCategoryNode,
                    $localeName,
                    $storeName
                );
            }
        }

        return $cachedCategoryNodeData;
    }
}
