<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToStorageClientInterface;
use Spryker\Client\ProductRelationStorage\Dependency\Service\ProductRelationStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductRelationStorage\ProductRelationStorageConfig;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Shared\ProductRelationStorage\ProductRelationStorageConfig as SharedProductRelationStorageConfig;

class ProductAbstractRelationStorageReader implements ProductAbstractRelationStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductRelationStorage\Dependency\Service\ProductRelationStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface|null
     */
    protected static $storageKeyBuilder;

    /**
     * @param \Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductRelationStorage\Dependency\Service\ProductRelationStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductRelationStorageToStorageClientInterface $storageClient,
        ProductRelationStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer|null
     */
    public function findProductAbstractRelation(int $idProductAbstract, string $storeName)
    {
        $productAbstractRelationStorageData = $this->getStorageData($idProductAbstract, $storeName);

        if (!$productAbstractRelationStorageData) {
            return null;
        }

        $productAbstractRelationStorageTransfer = new ProductAbstractRelationStorageTransfer();

        return $productAbstractRelationStorageTransfer->fromArray($productAbstractRelationStorageData, true);
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return array|null
     */
    protected function getStorageData(int $idProductAbstract, string $storeName): ?array
    {
        if (ProductRelationStorageConfig::isCollectorCompatibilityMode()) {
            $clientLocatorClassName = Locator::class;
            /** @var \Spryker\Client\ProductRelation\ProductRelationClientInterface $productRelationClient */
            $productRelationClient = $clientLocatorClassName::getInstance()->productRelation()->client();

            $collectorData = $productRelationClient->getProductRelationsByIdProductAbstract($idProductAbstract);

            $productAbstractRelationCollectorData = [
                'id_product_abstract' => $idProductAbstract,
                'product_relations' => [],
            ];

            foreach ($collectorData as $key => $storageProductRelationsTransfer) {
                $relation = [
                    'key' => 'related-products',
                    'product_abstract_ids' => [],
                    'is_active' => $storageProductRelationsTransfer->getIsActive(),
                ];

                $abstractProducts = $storageProductRelationsTransfer->getAbstractProducts();
                foreach ($abstractProducts as $position => $storageProductAbstractRelationTransfer) {
                    $relation['product_abstract_ids'][$storageProductAbstractRelationTransfer->getIdProductAbstract()] = $position + 1;
                }

                $productAbstractRelationCollectorData['product_relations'][] = $relation;
            }

            return $productAbstractRelationCollectorData;
        }

        $key = $this->generateKey($idProductAbstract, $storeName);

        return $this->storageClient->get($key);
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey($idProductAbstract, string $storeName): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference((string)$idProductAbstract)
            ->setStore($storeName);

        return $this->getStorageKeyBuilder()->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function getStorageKeyBuilder(): SynchronizationKeyGeneratorPluginInterface
    {
        if (static::$storageKeyBuilder === null) {
            static::$storageKeyBuilder = $this->synchronizationService->getStorageKeyBuilder(SharedProductRelationStorageConfig::PRODUCT_ABSTRACT_RELATION_RESOURCE_NAME);
        }

        return static::$storageKeyBuilder;
    }
}
