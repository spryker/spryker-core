<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedUnpublisher;

use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface;

class ProductDiscontinuedUnpublisher implements ProductDiscontinuedUnpublisherInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface
     */
    protected $discontinuedStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface
     */
    protected $productDiscontinuedStorageRepository;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface $discontinuedStorageEntityManager
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface $productDiscontinuedStorageRepository
     */
    public function __construct(
        ProductDiscontinuedStorageEntityManagerInterface $discontinuedStorageEntityManager,
        ProductDiscontinuedStorageRepositoryInterface $productDiscontinuedStorageRepository
    ) {
        $this->discontinuedStorageEntityManager = $discontinuedStorageEntityManager;
        $this->productDiscontinuedStorageRepository = $productDiscontinuedStorageRepository;
    }

    /**
     * @param array $productDiscontinuedIds
     *
     * @return void
     */
    public function unpublish(array $productDiscontinuedIds): void
    {
        $productDiscontinuedStorageEntityTransfers = $this->findProductDiscontinuedStorageEntitiesByIds($productDiscontinuedIds);
        
        foreach ($productDiscontinuedStorageEntityTransfers as $productDiscontinuedStorageEntityTransfer) {
            $this->discontinuedStorageEntityManager->deleteProductDiscontinuedStorageEntity(
                $productDiscontinuedStorageEntityTransfer
            );
        }
    }

    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return \Generated\Shared\Transfer\SpyProductDiscontinuedStorageEntityTransfer[]
     */
    protected function findProductDiscontinuedStorageEntitiesByIds(array $productDiscontinuedIds): array
    {
        return $this->productDiscontinuedStorageRepository->findProductDiscontinuedStorageEntitiesByIds($productDiscontinuedIds);
    }
}
