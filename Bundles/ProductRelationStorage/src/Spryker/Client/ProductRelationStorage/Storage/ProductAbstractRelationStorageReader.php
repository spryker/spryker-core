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
     * @param \Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductRelationStorage\Dependency\Service\ProductRelationStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(ProductRelationStorageToStorageClientInterface $storageClient, ProductRelationStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer|null
     */
    public function findProductAbstractRelation($idProductAbstract)
    {
        $productAbstractRelationStorageData = $this->getStorageData($idProductAbstract);

        if (!$productAbstractRelationStorageData) {
            return null;
        }

        $productAbstractRelationStorageTransfer = new ProductAbstractRelationStorageTransfer();

        return $productAbstractRelationStorageTransfer->fromArray($productAbstractRelationStorageData, true);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array|null
     */
    protected function getStorageData(int $idProductAbstract)
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
        $key = $this->generateKey($idProductAbstract);
        $productAbstractRelationStorageData = $this->storageClient->get($key);

        return $productAbstractRelationStorageData;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function generateKey($idProductAbstract)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference((string)$idProductAbstract);

        return $this->synchronizationService
            ->getStorageKeyBuilder(SharedProductRelationStorageConfig::PRODUCT_ABSTRACT_RELATION_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
