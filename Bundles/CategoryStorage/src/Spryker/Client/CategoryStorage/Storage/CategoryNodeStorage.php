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
use Spryker\Client\CategoryStorage\Exception\NotFoundCategoryNodeDataCacheException;
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
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById($idCategoryNode, $localeName)
    {
        $categoryData = $this->getStorageData($idCategoryNode, $localeName);

        $categoryNodeStorageTransfer = new CategoryNodeStorageTransfer();
        if ($categoryData) {
            $categoryNodeStorageTransfer->fromArray($categoryData, true);
        }

        return $categoryNodeStorageTransfer;
    }

    /**
     * @param int[] $categoryNodeIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function getCategoryNodeByIds(array $categoryNodeIds, string $localeName): array
    {
        $cachedCategoryNodeStorageData = $this->getCategoryNodeDataCacheByIdCategoryNodesAndLocaleName($categoryNodeIds, $localeName);
        $categoryNodeIds = array_diff($categoryNodeIds, array_keys($cachedCategoryNodeStorageData));

        if (!$categoryNodeIds) {
            return $cachedCategoryNodeStorageData;
        }

        $categoryNodes = $this->getStorageDataByNodeIds($categoryNodeIds, $localeName);

        $categoryNodeStorageTransfers = [];
        foreach ($categoryNodes as $categoryNode) {
            $categoryNodeStorageTransfers[] = $this->mapCategoryNodeStorageDataToCategoryNodeStorageTransfer($categoryNode, $localeName);
        }

        return array_merge($cachedCategoryNodeStorageData, $categoryNodeStorageTransfers);
    }

    /**
     * @param string $categoryNodeStorageData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer|null
     */
    protected function mapCategoryNodeStorageDataToCategoryNodeStorageTransfer(string $categoryNodeStorageData, string $localeName): ?CategoryNodeStorageTransfer
    {
        $decodedCategoryNodeStorageData = json_decode($categoryNodeStorageData, true);

        if (!$decodedCategoryNodeStorageData) {
            return null;
        }

        $categoryNodeStorageTransfer = (new CategoryNodeStorageTransfer())
            ->fromArray($decodedCategoryNodeStorageData, true);

        $this->cacheCategoryNodeDataByIdCategoryNodeAndLocaleName(
            $categoryNodeStorageTransfer->getNodeId(),
            $localeName,
            $categoryNodeStorageTransfer
        );

        return $categoryNodeStorageTransfer;
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return array|null
     */
    protected function getStorageData(int $idCategoryNode, string $localeName)
    {
        if (CategoryStorageConfig::isCollectorCompatibilityMode()) {
            return $this->getCollectorStorageData($idCategoryNode, $localeName);
        }

        $categoryNodeKey = $this->generateKey($idCategoryNode, $localeName);
        $categoryData = $this->storageClient->get($categoryNodeKey);

        return $categoryData;
    }

    /**
     * @param int[] $categoryNodeIds
     * @param string $localeName
     *
     * @return string[]
     */
    protected function getStorageDataByNodeIds(array $categoryNodeIds, string $localeName): array
    {
        $categoryNodeKeys = [];
        foreach ($categoryNodeIds as $categoryNodeId) {
            $categoryNodeKeys[] = $this->generateKey($categoryNodeId, $localeName);
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
     *
     * @return string
     */
    protected function generateKey(int $idCategoryNode, string $localeName): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference((string)$idCategoryNode);
        $synchronizationDataTransfer->setLocale($localeName);

        return $this->synchronizationService
            ->getStorageKeyBuilder(CategoryStorageConstants::CATEGORY_NODE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer|null $categoryNodeStorageTransfer
     *
     * @return void
     */
    protected function cacheCategoryNodeDataByIdCategoryNodeAndLocaleName(
        int $idCategoryNode,
        string $localeName,
        ?CategoryNodeStorageTransfer $categoryNodeStorageTransfer
    ): void {
        static::$categoryNodeDataCache[$idCategoryNode][$localeName] = $categoryNodeStorageTransfer;
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @throws \Spryker\Client\CategoryStorage\Exception\NotFoundCategoryNodeDataCacheException
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    protected function getCategoryNodeDataCacheByIdCategoryNodeAndLocaleName(int $idCategoryNode, string $localeName): CategoryNodeStorageTransfer
    {
        if (!$this->hasCategoryNodeDataCacheByIdCategoryNodeAndLocaleName($idCategoryNode, $localeName)) {
            throw new NotFoundCategoryNodeDataCacheException();
        }

        return static::$categoryNodeDataCache[$idCategoryNode][$localeName];
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return bool
     */
    protected function hasCategoryNodeDataCacheByIdCategoryNodeAndLocaleName(int $idCategoryNode, string $localeName): bool
    {
        return isset(static::$categoryNodeDataCache[$idCategoryNode][$localeName]);
    }

    /**
     * @param int[] $categoryNodeIds
     * @param string $localeName
     *
     * @return array
     */
    protected function getCategoryNodeDataCacheByIdCategoryNodesAndLocaleName(array $categoryNodeIds, string $localeName)
    {
        $cachedCategoryNodeData = [];
        foreach ($categoryNodeIds as $idCategoryNode) {
            if ($this->hasCategoryNodeDataCacheByIdCategoryNodeAndLocaleName($idCategoryNode, $localeName)) {
                $cachedCategoryNodeData[$idCategoryNode] = $this->getCategoryNodeDataCacheByIdCategoryNodeAndLocaleName($idCategoryNode, $localeName);
            }
        }

        return $cachedCategoryNodeData;
    }
}
