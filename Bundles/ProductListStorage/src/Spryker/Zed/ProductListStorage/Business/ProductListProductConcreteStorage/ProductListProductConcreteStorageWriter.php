<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductListProductConcreteStorage;

use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
use Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage;
use Spryker\Zed\ProductListStorage\Business\ProductAbstract\ProductAbstractReaderInterface;
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
     * @var \Spryker\Zed\ProductListStorage\Business\ProductAbstract\ProductAbstractReaderInterface
     */
    protected $productAbstractReader;

    /**
     * @var bool
     */
    protected $isSendingToQueue;

    /**
     * @param \Spryker\Zed\ProductListStorage\Business\ProductAbstract\ProductAbstractReaderInterface $productAbstractReader
     * @param \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface $productListFacade
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface $productListStorageRepository
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductAbstractReaderInterface $productAbstractReader,
        ProductListStorageToProductListFacadeInterface $productListFacade,
        ProductListStorageRepositoryInterface $productListStorageRepository,
        bool $isSendingToQueue
    ) {
        $this->productListFacade = $productListFacade;
        $this->productListStorageRepository = $productListStorageRepository;
        $this->productAbstractReader = $productAbstractReader;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publish(array $productConcreteIds): void
    {
        $productConcreteProductListStorageEntities = $this->findProductConcreteProductListStorageEntities($productConcreteIds);
        $indexedProductConcreteProductListStorageEntities = $this->indexProductConcreteProductListStorageEntities($productConcreteProductListStorageEntities);
        foreach ($productConcreteIds as $idProduct) {
            $productConcreteProductListStorageEntity = $this->getProductConcreteProductListStorageEntity($idProduct, $indexedProductConcreteProductListStorageEntities);
            if ($this->saveProductConcreteProductListStorageEntity($idProduct, $productConcreteProductListStorageEntity)) {
                unset($indexedProductConcreteProductListStorageEntities[$idProduct]);
            }
        }

        $this->deleteProductConcreteProductListStorageEntities($indexedProductConcreteProductListStorageEntities);
    }

    /**
     * @param int $idProduct
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage $productConcreteProductListStorageEntity
     *
     * @return bool
     */
    protected function saveProductConcreteProductListStorageEntity(
        int $idProduct,
        SpyProductConcreteProductListStorage $productConcreteProductListStorageEntity
    ): bool {
        $productConcreteProductListsStorageTransfer = $this->getProductConcreteProductListsStorageTransfer($idProduct);
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
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer
     */
    protected function getProductConcreteProductListsStorageTransfer(int $idProduct): ProductConcreteProductListStorageTransfer
    {
        $productConcreteProductListsStorageTransfer = new ProductConcreteProductListStorageTransfer();
        $productConcreteProductListsStorageTransfer->setIdProductConcrete($idProduct)
            ->setIdBlacklists($this->findProductConcreteBlacklistIdsByIdProductConcrete($idProduct))
            ->setIdWhitelists($this->findProductConcreteWhitelistIdsByIdProductConcrete($idProduct));

        return $productConcreteProductListsStorageTransfer;
    }

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    protected function findProductConcreteBlacklistIdsByIdProductConcrete(int $idProduct): array
    {
        $productAbstractIds = $this->productAbstractReader->findProductAbstractIdsByProductConcreteIds([$idProduct]);
        $idProductAbstract = reset($productAbstractIds);

        $blacklistIds = array_merge(
            $this->productListFacade->getProductBlacklistIdsByIdProduct($idProduct),
            $this->productListFacade->getProductBlacklistIdsByIdProductAbstract($idProductAbstract)
        );

        return array_unique($blacklistIds);
    }

    /**
     * @param int $idProduct
     *
     * @return int[]
     */
    protected function findProductConcreteWhitelistIdsByIdProductConcrete(int $idProduct): array
    {
        $productAbstractIds = $this->productAbstractReader->findProductAbstractIdsByProductConcreteIds([$idProduct]);
        $idProductAbstract = reset($productAbstractIds);

        $whitelistIds = array_merge(
            $this->productListFacade->getProductWhitelistIdsByIdProduct($idProduct),
            $this->productListFacade->getCategoryWhitelistIdsByIdProductAbstract($idProductAbstract)
        );

        return array_unique($whitelistIds);
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
