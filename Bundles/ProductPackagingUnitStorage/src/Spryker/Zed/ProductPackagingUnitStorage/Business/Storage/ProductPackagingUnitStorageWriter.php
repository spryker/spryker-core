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
        $productConcretePackagingTransfers = $this->productPackagingUnitStorageRepository
            ->findPackagingProductsByProductConcreteIds($productConcreteIds);

        $this->storeData($productConcretePackagingTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer[] $productConcretePackagingTransfers
     *
     * @return void
     */
    protected function storeData(array $productConcretePackagingTransfers): void
    {
        foreach ($productConcretePackagingTransfers as $productConcretePackagingTransfer) {
            $this->productPackagingUnitStorageEntityManager->saveProductConcretePackagingStorage($productConcretePackagingTransfer);
        }
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function unpublishProductPackagingUnit(array $productConcreteIds): void
    {
        $productConcretePackagingStorageEntities = $this->productPackagingUnitStorageRepository
            ->findProductConcretePackagingStorageEntitiesByProductConcreteIds($productConcreteIds);

        foreach ($productConcretePackagingStorageEntities as $productConcretePackagingStorageEntity) {
            $this->productPackagingUnitStorageEntityManager->deleteProductConcretePackagingStorage($productConcretePackagingStorageEntity);
        }
    }
}
