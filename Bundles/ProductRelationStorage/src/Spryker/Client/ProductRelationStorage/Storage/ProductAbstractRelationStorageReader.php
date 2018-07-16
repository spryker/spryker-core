<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToStorageClientInterface;
use Spryker\Client\ProductRelationStorage\Dependency\Service\ProductRelationStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductRelationStorage\ProductRelationStorageConfig;

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
        $key = $this->generateKey($idProductAbstract);
        $productAbstractRelationStorageData = $this->storageClient->get($key);

        if (!$productAbstractRelationStorageData) {
            return null;
        }

        $productAbstractRelationStorageTransfer = new ProductAbstractRelationStorageTransfer();

        return $productAbstractRelationStorageTransfer->fromArray($productAbstractRelationStorageData, true);
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
            ->setReference($idProductAbstract);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductRelationStorageConfig::PRODUCT_ABSTRACT_RELATION_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
