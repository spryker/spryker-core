<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductListProductConcreteStorage;

use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
use Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface;
use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface;
use Spryker\Zed\ProductListStorage\ProductListStorageConfig;

class ProductListProductConcreteStorageWriter implements ProductListProductConcreteStorageWriterInterface
{
    use InstancePoolingTrait;

    /**
     * @var \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @var \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface
     */
    protected $productListStorageRepository;

    /**
     * @var \Spryker\Zed\ProductListStorage\ProductListStorageConfig
     */
    protected $productListStorageConfig;

    /**
     * @param \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface $productListFacade
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface $productListStorageRepository
     * @param \Spryker\Zed\ProductListStorage\ProductListStorageConfig $productListStorageConfig
     */
    public function __construct(
        ProductListStorageToProductListFacadeInterface $productListFacade,
        ProductListStorageRepositoryInterface $productListStorageRepository,
        ProductListStorageConfig $productListStorageConfig
    ) {
        $this->productListFacade = $productListFacade;
        $this->productListStorageRepository = $productListStorageRepository;
        $this->productListStorageConfig = $productListStorageConfig;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publish(array $productConcreteIds): void
    {
        $isPoolingStateChanged = $this->disableInstancePooling();

        $productConcreteIds = array_unique($productConcreteIds);
        $productLists = $this->productListFacade->getProductListsIdsByProductIds($productConcreteIds);

        $productConcreteIdsChunks = array_chunk(
            $productConcreteIds,
            $this->productListStorageConfig->getProductListProductConcretePublishChunkSize()
        );

        foreach ($productConcreteIdsChunks as $productConcreteIdsChunk) {
            $productConcreteProductListStorageEntities = $this->findProductConcreteProductListStorageEntities($productConcreteIdsChunk);
            $indexedProductConcreteProductListStorageEntities = $this->indexProductConcreteProductListStorageEntities($productConcreteProductListStorageEntities);

            $savedProductConcreteProductListStorageEntities = $this->saveProductConcreteProductListStorageEntities(
                $productConcreteIdsChunk,
                $indexedProductConcreteProductListStorageEntities,
                $productLists
            );

            $this->deleteProductConcreteProductListStorageEntitiesWithoutLists(
                $indexedProductConcreteProductListStorageEntities,
                $savedProductConcreteProductListStorageEntities
            );
        }

        if ($isPoolingStateChanged) {
            $this->enableInstancePooling();
        }
    }

    /**
     * @param array $productConcreteIds
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[] $productConcreteProductListStorageEntities
     * @param array $productLists
     *
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[]
     */
    protected function saveProductConcreteProductListStorageEntities(
        array $productConcreteIds,
        array $productConcreteProductListStorageEntities,
        array $productLists
    ): array {
        $savedProductConcreteProductListStorageEntities = [];

        foreach ($productConcreteIds as $idProductConcrete) {
            $productConcreteProductListsStorageTransfer = $this->getProductConcreteProductListsStorageTransfer(
                $idProductConcrete,
                $productLists
            );

            if (
                !$productConcreteProductListsStorageTransfer->getIdBlacklists()
                && !$productConcreteProductListsStorageTransfer->getIdWhitelists()
            ) {
                continue;
            }

            $productConcreteProductListStorageEntity = $this->getProductConcreteProductListStorageEntity(
                $idProductConcrete,
                $productConcreteProductListStorageEntities
            );

            $productConcreteProductListStorageEntity->setFkProduct($idProductConcrete)
                ->setData($productConcreteProductListsStorageTransfer->toArray())
                ->setIsSendingToQueue($this->productListStorageConfig->isSendingToQueue())
                ->save();

            $savedProductConcreteProductListStorageEntities[$idProductConcrete] = $productConcreteProductListStorageEntity;
        }

        return $savedProductConcreteProductListStorageEntities;
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
    protected function getProductConcreteProductListStorageEntity(
        int $idProduct,
        array $indexedProductConcreteProductListStorageEntities
    ): SpyProductConcreteProductListStorage {
        if (isset($indexedProductConcreteProductListStorageEntities[$idProduct])) {
            return $indexedProductConcreteProductListStorageEntities[$idProduct];
        }

        return new SpyProductConcreteProductListStorage();
    }

    /**
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[] $productConcreteProductListStorageEntities
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[] $productConcreteProductListStorageEntitiesWithLists
     *
     * @return void
     */
    protected function deleteProductConcreteProductListStorageEntitiesWithoutLists(
        array $productConcreteProductListStorageEntities,
        array $productConcreteProductListStorageEntitiesWithLists
    ): void {
        $productConcreteProductListStorageEntitiesToDelete = array_diff_key(
            $productConcreteProductListStorageEntities,
            $productConcreteProductListStorageEntitiesWithLists
        );

        foreach ($productConcreteProductListStorageEntitiesToDelete as $productConcreteProductListStorageEntity) {
            $productConcreteProductListStorageEntity->delete();
        }
    }
}
