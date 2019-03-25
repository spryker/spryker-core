<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Storage;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTreeStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageConfig;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface;
use Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface;
use Spryker\Client\Kernel\Locator;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;

class CategoryTreeStorageReader implements CategoryTreeStorageReaderInterface
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
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]|\ArrayObject
     */
    public function getCategories($locale)
    {
        $categories = $this->getStorageData($locale);
        if (!$categories) {
            return new ArrayObject();
        }

        $categoryTreeStorageTransfer = new CategoryTreeStorageTransfer();
        $categoryTreeStorageTransfer->fromArray($categories, true);

        return $categoryTreeStorageTransfer->getCategoryNodesStorage();
    }

    /**
     * @param string $localeName
     *
     * @return array|null
     */
    protected function getStorageData(string $localeName)
    {
        if (CategoryStorageConfig::isCollectorCompatibilityMode()) {
            $clientLocatorClassName = Locator::class;
            /** @var \Spryker\Client\CategoryExporter\CategoryExporterClientInterface $categoryExporterClient */
            $categoryExporterClient = $clientLocatorClassName::getInstance()->categoryExporter()->client();
            $collectorData = $categoryExporterClient->getNavigationCategories($localeName);
            $collectorCategories = [
                'category_nodes_storage' => $this->filterCollectorDataRecursive($collectorData),
            ];

            return $collectorCategories;
        }

        $categoryTreeKey = $this->generateKey($localeName);
        $categories = $this->storageClient->get($categoryTreeKey);

        return $categories;
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
            if (empty($category['parents'])) {
                unset($category['parents']);
            }
            if (isset($category['children'])) {
                $category['children'] = $this->filterCollectorDataRecursive($category['children']);
            }
            $filteredCategories[] = $category;
        }

        return $filteredCategories;
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    protected function generateKey($locale)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setLocale($locale);

        return $this->synchronizationService->getStorageKeyBuilder(CategoryStorageConstants::CATEGORY_TREE_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }
}
