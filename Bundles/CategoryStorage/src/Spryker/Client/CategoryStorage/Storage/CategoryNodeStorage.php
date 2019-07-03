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
     * @param string $locale
     *
     * @return string
     */
    protected function generateKey($idCategoryNode, $locale)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference($idCategoryNode);
        $synchronizationDataTransfer->setLocale($locale);

        return $this->synchronizationService->getStorageKeyBuilder(CategoryStorageConstants::CATEGORY_NODE_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }
}
