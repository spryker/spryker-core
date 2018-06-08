<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

use Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageQueryContainerInterface;

class ProductAbstractPackagingStorageWriter implements ProductAbstractPackagingStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var int[] Keys are store ids, values are store names.
     */
    protected $storeNameMapBuffer;

    /**
     * @param \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageQueryContainerInterface $queryContainer
     */
    public function __construct(
        ProductPackagingUnitStorageQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $productAbstractPackagingTransfers = $this->getProductAbstractPackagingTransfers($productAbstractIds);

        $this->storeData($productAbstractPackagingTransfers);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $productAbstractPackagingStorageEntities = $this->queryContainer
            ->queryProductAbstractPackagingStorageByProductAbstractIds($productAbstractIds);

        foreach ($productAbstractPackagingStorageEntities as $productAbstractPackagingStorageEntity) {
            $this->queryContainer->deleteProductAbstractPackagingStorage($productAbstractPackagingStorageEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer[] $productAbstractPackagingTransfers
     *
     * @return void
     */
    protected function storeData(array $productAbstractPackagingTransfers)
    {
        foreach ($productAbstractPackagingTransfers as $productAbstractPackagingTransfer) {
            $this->queryContainer->createProductAbstractPackagingStorage($productAbstractPackagingTransfer);
        }
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer[]
     */
    protected function getProductAbstractPackagingTransfers(array $productAbstractIds)
    {
        $productAbstractPackagingTransfers = [];

        foreach ($productAbstractIds as $productAbstractId) {
            $productAbstractPackagingTransfers[] = $this->queryContainer
                ->getProductAbstractPackagingTransferByProductAbstractId($productAbstractId);
        }

        return $productAbstractPackagingTransfers;
    }
}
