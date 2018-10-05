<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Business\Model;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer;
use Spryker\Zed\ProductQuantityStorage\Dependency\Facade\ProductQuantityStorageToProductQuantityFacadeInterface;
use Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageEntityManagerInterface;
use Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageRepositoryInterface;

class ProductQuantityStorageWriter implements ProductQuantityStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageEntityManagerInterface
     */
    protected $productQuantityStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageRepositoryInterface
     */
    protected $productQuantityStorageRepository;

    /**
     * @var \Spryker\Zed\ProductQuantityStorage\Dependency\Facade\ProductQuantityStorageToProductQuantityFacadeInterface
     */
    protected $productQuantityFacade;

    /**
     * @param \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageEntityManagerInterface $productQuantityStorageEntityManager
     * @param \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageRepositoryInterface $productQuantityStorageRepository
     * @param \Spryker\Zed\ProductQuantityStorage\Dependency\Facade\ProductQuantityStorageToProductQuantityFacadeInterface $productQuantityFacade
     */
    public function __construct(
        ProductQuantityStorageEntityManagerInterface $productQuantityStorageEntityManager,
        ProductQuantityStorageRepositoryInterface $productQuantityStorageRepository,
        ProductQuantityStorageToProductQuantityFacadeInterface $productQuantityFacade
    ) {
        $this->productQuantityStorageEntityManager = $productQuantityStorageEntityManager;
        $this->productQuantityStorageRepository = $productQuantityStorageRepository;
        $this->productQuantityFacade = $productQuantityFacade;
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publish(array $productIds): void
    {
        $productQuantityTransfers = $this->productQuantityFacade->findProductQuantityTransfersByProductIds($productIds);
        $productQuantityStorageEntities = $this->productQuantityStorageRepository->findProductQuantityStorageEntitiesByProductIds($productIds);
        $mappedProductQuantityStorageEntities = $this->mapProductQuantityStorageEntities($productQuantityStorageEntities);

        foreach ($productQuantityTransfers as $productQuantityTransfer) {
            $storageEntity = $this->selectStorageEntity($mappedProductQuantityStorageEntities, $productQuantityTransfer->getFkProduct());

            unset($mappedProductQuantityStorageEntities[$productQuantityTransfer->getFkProduct()]);

            $this->saveStorageEntity($storageEntity, $productQuantityTransfer);
        }

        $this->deleteStorageEntities($mappedProductQuantityStorageEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer $storageEntity
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     *
     * @return void
     */
    protected function saveStorageEntity(
        SpyProductQuantityStorageEntityTransfer $storageEntity,
        ProductQuantityTransfer $productQuantityTransfer
    ): void {
        $storageEntity
            ->setFkProduct($productQuantityTransfer->getFkProduct())
            ->setData($this->getStorageEntityData($productQuantityTransfer));

        $this->productQuantityStorageEntityManager->saveProductQuantityStorageEntity($storageEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer[] $mappedProductQuantityStorageEntities
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer
     */
    protected function selectStorageEntity(array $mappedProductQuantityStorageEntities, int $idProduct): SpyProductQuantityStorageEntityTransfer
    {
        if (isset($mappedProductQuantityStorageEntities[$idProduct])) {
            return $mappedProductQuantityStorageEntities[$idProduct];
        }

        return new SpyProductQuantityStorageEntityTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     *
     * @return array
     */
    protected function getStorageEntityData(ProductQuantityTransfer $productQuantityTransfer): array
    {
        return (new ProductQuantityStorageTransfer())
            ->fromArray($productQuantityTransfer->toArray(), true)
            ->setIdProduct($productQuantityTransfer->getFkProduct())
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer[] $productQuantityStorageEntities
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer[]
     */
    protected function mapProductQuantityStorageEntities(array $productQuantityStorageEntities): array
    {
        $mappedProductQuantityStorageEntities = [];
        foreach ($productQuantityStorageEntities as $entity) {
            $mappedProductQuantityStorageEntities[$entity->getFkProduct()] = $entity;
        }

        return $mappedProductQuantityStorageEntities;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer[] $mappedProductQuantityStorageEntities
     *
     * @return void
     */
    protected function deleteStorageEntities(array $mappedProductQuantityStorageEntities): void
    {
        foreach ($mappedProductQuantityStorageEntities as $productQuantityStorageEntity) {
            $this->productQuantityStorageEntityManager->deleteProductQuantityStorage($productQuantityStorageEntity->getIdProductQuantityStorage());
        }
    }
}
