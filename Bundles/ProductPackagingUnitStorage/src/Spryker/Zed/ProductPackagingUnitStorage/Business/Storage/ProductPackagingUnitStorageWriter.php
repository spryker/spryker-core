<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

use Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface;
use Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface;

class ProductPackagingUnitStorageWriter implements ProductPackagingUnitStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface
     */
    protected $productPackagingUnitStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface
     */
    protected $productPackagingUnitStorageRepository;

    /**
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface $productPackagingUnitStorageEntityManager
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface $productPackagingUnitStorageRepository
     */
    public function __construct(
        ProductPackagingUnitStorageEntityManagerInterface $productPackagingUnitStorageEntityManager,
        ProductPackagingUnitStorageRepositoryInterface $productPackagingUnitStorageRepository
    ) {
        $this->productPackagingUnitStorageEntityManager = $productPackagingUnitStorageEntityManager;
        $this->productPackagingUnitStorageRepository = $productPackagingUnitStorageRepository;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publishProductPackagingUnit(array $productConcreteIds): void
    {
        $productPackagingUnitStorageTransfers = $this->productPackagingUnitStorageRepository
            ->findPackagingProductsByProductConcreteIds($productConcreteIds);

        $this->storeData($productPackagingUnitStorageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer[] $productPackagingUnitStorageTransfers
     *
     * @return void
     */
    protected function storeData(array $productPackagingUnitStorageTransfers): void
    {
        foreach ($productPackagingUnitStorageTransfers as $productPackagingUnitStorageTransfer) {
            $this->productPackagingUnitStorageEntityManager->saveProductPackagingUnitStorage($productPackagingUnitStorageTransfer);
        }
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function unpublishProductPackagingUnit(array $productConcreteIds): void
    {
        $productPackagingUnitStorageEntities = $this->productPackagingUnitStorageRepository
            ->findProductPackagingUnitStorageEntitiesByProductConcreteIds($productConcreteIds);

        foreach ($productPackagingUnitStorageEntities as $productPackagingUnitStorageEntity) {
            $this->productPackagingUnitStorageEntityManager->deleteProductPackagingUnitStorage($productPackagingUnitStorageEntity);
        }
    }
}
