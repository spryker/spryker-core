<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductListProductConcreteStorage;

use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
use Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface;
use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface;

class ProductListProductConcreteStorageWriter implements ProductListProductConcreteStorageWriterInterface
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
     * @deprecated Will be removed without replacement.
     *
     * @var bool
     */
    protected $isSendingToQueue;

    /**
     * @param \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface $productListFacade
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface $productListStorageRepository
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductListStorageToProductListFacadeInterface $productListFacade,
        ProductListStorageRepositoryInterface $productListStorageRepository,
        bool $isSendingToQueue
    ) {
        $this->productListFacade = $productListFacade;
        $this->productListStorageRepository = $productListStorageRepository;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publish(array $productConcreteIds): void
    {
        $productLists = $this->productListFacade->getProductListsIdsByProductIds($productConcreteIds);

        $productConcreteProductListStorageEntities = $this->findProductConcreteProductListStorageEntities($productConcreteIds);
        $indexedProductConcreteProductListStorageEntities = $this->indexProductConcreteProductListStorageEntities($productConcreteProductListStorageEntities);
        foreach ($productConcreteIds as $idProduct) {
            $productConcreteProductListStorageEntity = $this->getProductConcreteProductListStorageEntity($idProduct, $indexedProductConcreteProductListStorageEntities);
            if ($this->saveProductConcreteProductListStorageEntity($idProduct, $productConcreteProductListStorageEntity, $productLists)) {
                unset($indexedProductConcreteProductListStorageEntities[$idProduct]);
            }
        }

        $this->deleteProductConcreteProductListStorageEntities($indexedProductConcreteProductListStorageEntities);
    }

    /**
     * @param int $idProduct
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage $productConcreteProductListStorageEntity
     * @param array $productLists
     *
     * @return bool
     */
    protected function saveProductConcreteProductListStorageEntity(
        int $idProduct,
        SpyProductConcreteProductListStorage $productConcreteProductListStorageEntity,
        array $productLists
    ): bool {
        $productConcreteProductListsStorageTransfer = $this->getProductConcreteProductListsStorageTransfer($idProduct, $productLists);
        if ($productConcreteProductListsStorageTransfer->getIdBlacklists() || $productConcreteProductListsStorageTransfer->getIdWhitelists()) {
            $productConcreteProductListStorageEntity->setFkProduct($idProduct)
                ->setData($productConcreteProductListsStorageTransfer->toArray())
                ->setIsSendingToQueue($this->isSendingToQueue)
                ->save();

            return true;
        }

        return false;
    }

    /**
     * @param int $idProductConcrete
     * @param array $productLists
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer
     */
    protected function getProductConcreteProductListsStorageTransfer(int $idProductConcrete, array $productLists): ProductConcreteProductListStorageTransfer
    {
        $productConcreteProductListsStorageTransfer = new ProductConcreteProductListStorageTransfer();
        $productConcreteProductListsStorageTransfer->setIdProductConcrete($idProductConcrete)
            ->setIdBlacklists($this->findProductConcreteBlacklistIdsByIdProductConcrete($idProductConcrete, $productLists))
            ->setIdWhitelists($this->findProductConcreteWhitelistIdsByIdProductConcrete($idProductConcrete, $productLists));

        return $productConcreteProductListsStorageTransfer;
    }

    /**
     * @param int $idProductConcrete
     * @param array $productLists
     *
     * @return int[]
     */
    protected function findProductConcreteBlacklistIdsByIdProductConcrete(int $idProductConcrete, array $productLists): array
    {
        return $productLists[$idProductConcrete][$this->productListStorageRepository->getProductListBlacklistEnumValue()] ?? [];
    }

    /**
     * @param int $idProductConcrete
     * @param array $productLists
     *
     * @return int[]
     */
    protected function findProductConcreteWhitelistIdsByIdProductConcrete(int $idProductConcrete, array $productLists): array
    {
        return $productLists[$idProductConcrete][$this->productListStorageRepository->getProductListWhitelistEnumValue()] ?? [];
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[]
     */
    protected function findProductConcreteProductListStorageEntities(array $productConcreteIds): array
    {
        return $this->productListStorageRepository->findProductConcreteProductListStorageEntities($productConcreteIds);
    }

    /**
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[] $productConcreteProductListStorageEntities
     *
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[]
     */
    protected function indexProductConcreteProductListStorageEntities(array $productConcreteProductListStorageEntities): array
    {
        $indexedProductConcreteProductListStorageEntities = [];

        foreach ($productConcreteProductListStorageEntities as $entity) {
            $indexedProductConcreteProductListStorageEntities[$entity->getFkProduct()] = $entity;
        }

        return $indexedProductConcreteProductListStorageEntities;
    }

    /**
     * @param int $idProduct
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[] $indexedProductConcreteProductListStorageEntities
     *
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage
     */
    protected function getProductConcreteProductListStorageEntity(int $idProduct, array $indexedProductConcreteProductListStorageEntities): SpyProductConcreteProductListStorage
    {
        if (isset($indexedProductConcreteProductListStorageEntities[$idProduct])) {
            return $indexedProductConcreteProductListStorageEntities[$idProduct];
        }

        return new SpyProductConcreteProductListStorage();
    }

    /**
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[] $productConcreteProductListStorageEntities
     *
     * @return void
     */
    protected function deleteProductConcreteProductListStorageEntities(array $productConcreteProductListStorageEntities): void
    {
        foreach ($productConcreteProductListStorageEntities as $productConcreteProductListStorageEntity) {
            $productConcreteProductListStorageEntity->delete();
        }
    }
}
