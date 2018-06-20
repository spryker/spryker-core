<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductListProductAbstractStorage;

use Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer;
use Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface;
use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageEntityManagerInterface;
use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface;

class ProductListProductAbstractStorageWriter implements ProductListProductAbstractStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @var \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface
     */
    protected $productListStorageRepository;

    /**
     * @var \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageEntityManagerInterface
     */
    protected $productListStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface $productListFacade
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface $productListStorageRepository
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageEntityManagerInterface $productListStorageEntityManager
     */
    public function __construct(
        ProductListStorageToProductListFacadeInterface $productListFacade,
        ProductListStorageRepositoryInterface $productListStorageRepository,
        ProductListStorageEntityManagerInterface $productListStorageEntityManager
    ) {
        $this->productListFacade = $productListFacade;
        $this->productListStorageRepository = $productListStorageRepository;
        $this->productListStorageEntityManager = $productListStorageEntityManager;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $productAbstractProductListStorageEntityTransfers = $this->findProductAbstractProductListStorageEntities($productAbstractIds);
        $indexedProductAbstractProductListStorageEntities = $this->indexProductAbstractProductListStorageEntities($productAbstractProductListStorageEntityTransfers);
        foreach ($productAbstractIds as $idProductAbstract) {
            $productAbstractProductListStorageEntity = $this->getProductAbstractProductListStorageEntity($idProductAbstract, $indexedProductAbstractProductListStorageEntities);
            unset($indexedProductAbstractProductListStorageEntities[$idProductAbstract]);

            $this->saveProductAbstractProductListStorageEntity($idProductAbstract, $productAbstractProductListStorageEntity);
        }

        $this->deleteProductAbstractProductListStorageEntities($indexedProductAbstractProductListStorageEntities);
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer $productAbstractProductListStorageEntity
     *
     * @return void
     */
    protected function saveProductAbstractProductListStorageEntity(
        int $idProductAbstract,
        SpyProductAbstractProductListStorageEntityTransfer $productAbstractProductListStorageEntity
    ): void {
        $productAbstractProductListsStorageTransfer = $this->getProductAbstractProductListsStorageTransfer($idProductAbstract);
        if ($productAbstractProductListsStorageTransfer->getIdWhitelists() || $productAbstractProductListsStorageTransfer->getIdBlacklists()) {
            $productAbstractProductListStorageEntity->setFkProductAbstract($idProductAbstract)
                ->setData($productAbstractProductListsStorageTransfer->toArray());

            $this->productListStorageEntityManager->saveProductAbstractProductListStorage($productAbstractProductListStorageEntity);
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer
     */
    protected function getProductAbstractProductListsStorageTransfer(int $idProductAbstract): ProductAbstractProductListStorageTransfer
    {
        $productAbstractProductListsStorageTransfer = new ProductAbstractProductListStorageTransfer();
        $productAbstractProductListsStorageTransfer->setIdProductAbstract($idProductAbstract)
            ->setIdBlacklists($this->findProductAbstractBlacklistIds($idProductAbstract))
            ->setIdWhitelists($this->findProductAbstractWhitelistIds($idProductAbstract));

        return $productAbstractProductListsStorageTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function findProductAbstractBlacklistIds(int $idProductAbstract): array
    {
        return $this->productListFacade->getProductAbstractBlacklistIdsIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    protected function findProductAbstractWhitelistIds(int $idProductAbstract): array
    {
        return $this->productListFacade->getProductAbstractWhitelistIdsByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer[]
     */
    protected function findProductAbstractProductListStorageEntities(array $productAbstractIds): array
    {
        return $this->productListStorageRepository->findProductAbstractProductListStorageEntities($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer[] $productAbstractProductListStorageEntities
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer[]
     */
    protected function indexProductAbstractProductListStorageEntities(array $productAbstractProductListStorageEntities): array
    {
        $indexedProductAbstractProductListStorageEntities = [];

        foreach ($productAbstractProductListStorageEntities as $entity) {
            $indexedProductAbstractProductListStorageEntities[$entity->getFkProductAbstract()] = $entity;
        }

        return $indexedProductAbstractProductListStorageEntities;
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer[] $indexedProductAbstractProductListStorageEntities
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer
     */
    protected function getProductAbstractProductListStorageEntity(int $idProductAbstract, array $indexedProductAbstractProductListStorageEntities): SpyProductAbstractProductListStorageEntityTransfer
    {
        if (isset($indexedProductAbstractProductListStorageEntities[$idProductAbstract])) {
            return $indexedProductAbstractProductListStorageEntities[$idProductAbstract];
        }

        return new SpyProductAbstractProductListStorageEntityTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer[] $productAbstractProductListStorageEntities
     *
     * @return void
     */
    protected function deleteProductAbstractProductListStorageEntities(array $productAbstractProductListStorageEntities): void
    {
        foreach ($productAbstractProductListStorageEntities as $productAbstractProductListStorageEntity) {
            $this->productListStorageEntityManager->deleteProductAbstractProductListStorage($productAbstractProductListStorageEntity->getIdProductAbstractProductListStorage());
        }
    }
}
