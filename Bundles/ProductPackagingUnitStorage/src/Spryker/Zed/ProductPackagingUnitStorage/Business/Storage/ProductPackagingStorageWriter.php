<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

use Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface;

class ProductPackagingStorageWriter implements ProductPackagingStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface
     */
    protected $productPackagingUnitStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingStorageReaderInterface
     */
    protected $productConcretePackagingUnitStorageReader;

    /**
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface $productPackagingUnitStorageEntityManager
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingStorageReaderInterface $productConcretePackagingStorageReader
     */
    public function __construct(
        ProductPackagingUnitStorageEntityManagerInterface $productPackagingUnitStorageEntityManager,
        ProductPackagingStorageReaderInterface $productConcretePackagingStorageReader
    ) {
        $this->productPackagingUnitStorageEntityManager = $productPackagingUnitStorageEntityManager;
        $this->productConcretePackagingUnitStorageReader = $productConcretePackagingStorageReader;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publishProductPackagingUnit(array $productConcreteIds): void
    {
        $productConcretePackagingTransfers = $this->productConcretePackagingUnitStorageReader
            ->getProductConcretePackagingStorageTransfer($productConcreteIds);

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
            $this->productPackagingUnitStorageEntityManager->saveProductConcretePackagingStorageEntity($productConcretePackagingTransfer);
        }
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function unpublishProductPackagingUnit(array $productConcreteIds): void
    {
        $productConcretePackagingStorageEntities = $this->productConcretePackagingUnitStorageReader
            ->getProductConcretePackagingStorageEntities($productConcreteIds);

        foreach ($productConcretePackagingStorageEntities as $productConcretePackagingStorageEntity) {
            $this->productPackagingUnitStorageEntityManager->deleteProductConcretePackagingStorageEntity($productConcretePackagingStorageEntity);
        }
    }
}
