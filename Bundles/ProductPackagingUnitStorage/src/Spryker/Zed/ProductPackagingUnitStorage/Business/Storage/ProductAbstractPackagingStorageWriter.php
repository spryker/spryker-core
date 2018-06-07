<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage;
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
            ->queryProductAbstractPackagingStorageByProductAbstractIds($productAbstractIds)
            ->find();

        foreach ($productAbstractPackagingStorageEntities as $productAbstractPackagingStorageEntity) {
            $productAbstractPackagingStorageEntity->delete();
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
            $storageEntity = new SpyProductAbstractPackagingStorage();
            $storageEntity->setFkProductAbstract($productAbstractPackagingTransfer->getIdProductAbstract());
            $storageEntity->setData($productAbstractPackagingTransfer->toArray());
            $storageEntity->save();
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
            $leadProductEntity = $this->queryContainer
                ->queryLeadProductByAbstractId($productAbstractId)
                ->findOne();
            $packageProductConcreteEntities = $this->queryContainer
                ->queryPackageProductsByAbstractId($productAbstractId)
                ->find();

            $productAbstractPackagingTransfers[] = $this->mapProductAbstractPackagingTransfer(
                $productAbstractId,
                $leadProductEntity,
                $packageProductConcreteEntities
            );
        }

        return $productAbstractPackagingTransfers;
    }

    /**
     * @param int $productAbstractId
     * @param \Orm\Zed\Product\Persistence\SpyProduct $leadProductEntity
     * @param \Orm\Zed\Product\Persistence\SpyProduct[] $packageProductConcreteEntities
     *
     * @return string
     */
    protected function mapProductAbstractPackagingTransfer(int $productAbstractId, $leadProductEntity, $packageProductConcreteEntities)
    {
        $productAbstractPackagingStorageTransfer = (new ProductAbstractPackagingStorageTransfer())
            ->setIdProductAbstract($productAbstractId)
            ->setLeadProduct($leadProductEntity->getIdProduct());
        $productAbstractPackagingTypes = [];

        foreach ($packageProductConcreteEntities as $packageProductConcreteEntity) {
            $productAbstractPackagingTypes[] = (new ProductConcretePackagingStorageTransfer())
                ->setIdProduct($packageProductConcreteEntity->getIdProduct())
                ->setName($packageProductConcreteEntity->getIdProduct())
                ->setDefaultAmount($packageProductConcreteEntity->getIdProduct())
                ->setIsVariable($packageProductConcreteEntity->getIdProduct())
                ->setAmountMin($packageProductConcreteEntity->getIdProduct())
                ->setAmountMax($packageProductConcreteEntity->getIdProduct())
                ->setAmountInterval($packageProductConcreteEntity->getIdProduct());
        }
        $productAbstractPackagingStorageTransfer->setTypes($productAbstractPackagingTypes);

        return $productAbstractPackagingStorageTransfer;
    }
}
