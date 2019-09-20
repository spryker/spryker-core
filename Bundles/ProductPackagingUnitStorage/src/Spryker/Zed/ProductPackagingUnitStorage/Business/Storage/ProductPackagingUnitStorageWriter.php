<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

use Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface;

class ProductPackagingUnitStorageWriter implements ProductPackagingUnitStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface
     */
    protected $productPackagingUnitStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingUnitStorageReaderInterface
     */
    protected $productPackagingUnitStorageReader;

    /**
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface $productPackagingUnitStorageEntityManager
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingUnitStorageReaderInterface $productPackagingUnitStorageReader
     */
    public function __construct(
        ProductPackagingUnitStorageEntityManagerInterface $productPackagingUnitStorageEntityManager,
        ProductPackagingUnitStorageReaderInterface $productPackagingUnitStorageReader
    ) {
        $this->productPackagingUnitStorageEntityManager = $productPackagingUnitStorageEntityManager;
        $this->productPackagingUnitStorageReader = $productPackagingUnitStorageReader;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publishProductPackagingUnit(array $productConcreteIds): void
    {
        $productConcretePackagingTransfers = $this->productPackagingUnitStorageReader
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
        $productConcretePackagingStorageEntities = $this->productPackagingUnitStorageReader
            ->getProductConcretePackagingStorageEntities($productConcreteIds);

        foreach ($productConcretePackagingStorageEntities as $productConcretePackagingStorageEntity) {
            $this->productPackagingUnitStorageEntityManager->deleteProductConcretePackagingStorageEntity($productConcretePackagingStorageEntity);
        }
    }
}
