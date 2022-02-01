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
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
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
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface|null
     */
    protected static $storageKeyBuilder;

    /**
     * @param \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageInterface $storageClient
     * @param \Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        CategoryStorageToStorageInterface $storageClient,
        CategoryStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param string $localeName
     * @param string $storeName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    public function getCategories(string $localeName, string $storeName): ArrayObject
    {
        $categories = $this->getStorageData($localeName, $storeName);
        if (!$categories) {
            return new ArrayObject();
        }

        $categoryTreeStorageTransfer = (new CategoryTreeStorageTransfer())->fromArray($categories, true);

        return $categoryTreeStorageTransfer->getCategoryNodesStorage();
    }

    /**
     * @param string $localeName
     * @param string $storeName
     *
     * @return array|null
     */
    protected function getStorageData(string $localeName, string $storeName): ?array
    {
        if (CategoryStorageConfig::isCollectorCompatibilityMode()) {
            $clientLocatorClass = Locator::class;
            /** @var \Generated\Zed\Ide\AutoCompletion&\Spryker\Shared\Kernel\LocatorLocatorInterface $locator */
            $locator = $clientLocatorClass::getInstance();
            $categoryExporterClient = $locator->categoryExporter()->client();

            $collectorData = $categoryExporterClient->getNavigationCategories($localeName);
            $collectorCategories = [
                'category_nodes_storage' => $this->filterCollectorDataRecursive($collectorData),
            ];

            return $collectorCategories;
        }

        $categoryTreeKey = $this->generateKey($localeName, $storeName);

        return $this->storageClient->get($categoryTreeKey);
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
     * @param string $localeName
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey(string $localeName, string $storeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setStore($storeName)
            ->setLocale($localeName);

        return $this->getStorageKeyBuilder()->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function getStorageKeyBuilder(): SynchronizationKeyGeneratorPluginInterface
    {
        if (static::$storageKeyBuilder === null) {
            static::$storageKeyBuilder = $this->synchronizationService->getStorageKeyBuilder(CategoryStorageConstants::CATEGORY_TREE_RESOURCE_NAME);
        }

        return static::$storageKeyBuilder;
    }
}
