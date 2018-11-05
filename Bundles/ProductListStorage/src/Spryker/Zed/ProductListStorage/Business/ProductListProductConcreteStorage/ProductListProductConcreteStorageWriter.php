<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductListProductConcreteStorage;

use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
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
        $productListBuffer = $this->productListFacade->getProductListsIdsByIdProductIn($productConcreteIds);

        $productConcreteProductListStorageEntities = $this->findProductConcreteProductListStorageEntities($productConcreteIds);
        $indexedProductConcreteProductListStorageEntities = $this->indexProductConcreteProductListStorageEntities($productConcreteProductListStorageEntities);
        foreach ($productConcreteIds as $idProduct) {
            $productConcreteProductListStorageEntity = $this->getProductConcreteProductListStorageEntity($idProduct, $indexedProductConcreteProductListStorageEntities);
            if ($this->saveProductConcreteProductListStorageEntity($idProduct, $productConcreteProductListStorageEntity, $productListBuffer)) {
                unset($indexedProductConcreteProductListStorageEntities[$idProduct]);
            }
        }

        $this->deleteProductConcreteProductListStorageEntities($indexedProductConcreteProductListStorageEntities);
    }

    /**
     * @param int $idProduct
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage $productConcreteProductListStorageEntity
     * @param array $productListBuffer
     *
     * @return bool
     */
    protected function saveProductConcreteProductListStorageEntity(
        int $idProduct,
        SpyProductConcreteProductListStorage $productConcreteProductListStorageEntity,
        array $productListBuffer
    ): bool {
        $productConcreteProductListsStorageTransfer = $this->getProductConcreteProductListsStorageTransfer($idProduct, $productListBuffer);
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
     * @param array $productListBuffer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer
     */
    protected function getProductConcreteProductListsStorageTransfer(int $idProductConcrete, array $productListBuffer): ProductConcreteProductListStorageTransfer
    {
        $productConcreteProductListsStorageTransfer = new ProductConcreteProductListStorageTransfer();
        $productConcreteProductListsStorageTransfer->setIdProductConcrete($idProductConcrete)
            ->setIdBlacklists($this->findProductConcreteBlacklistIdsByIdProductConcrete($idProductConcrete, $productListBuffer))
            ->setIdWhitelists($this->findProductConcreteWhitelistIdsByIdProductConcrete($idProductConcrete, $productListBuffer));

        return $productConcreteProductListsStorageTransfer;
    }

    /**
     * @param int $idProductConcrete
     * @param array $productListBuffer
     *
     * @return int[]
     */
    protected function findProductConcreteBlacklistIdsByIdProductConcrete(int $idProductConcrete, array $productListBuffer): array
    {
        return $productListBuffer[$idProductConcrete][SpyProductListTableMap::COL_TYPE_BLACKLIST] ?? [];
    }

    /**
     * @param int $idProductConcrete
     * @param array $productListBuffer
     *
     * @return int[]
     */
    protected function findProductConcreteWhitelistIdsByIdProductConcrete(int $idProductConcrete, array $productListBuffer): array
    {
        return $productListBuffer[$idProductConcrete][SpyProductListTableMap::COL_TYPE_WHITELIST] ?? [];
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
