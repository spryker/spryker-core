<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductListProductAbstractStorage;

use Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer;
use Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface;
use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface;
use Spryker\Zed\ProductListStorage\ProductListStorageConfig;

class ProductListProductAbstractStorageWriter implements ProductListProductAbstractStorageWriterInterface
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
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $isPoolingStateChanged = $this->disableInstancePooling();

        $productAbstractIds = array_unique($productAbstractIds);
        $productLists = $this->productListFacade->getProductAbstractListIdsByProductAbstractIds($productAbstractIds);

        $productAbstractIdsChunks = array_chunk(
            $productAbstractIds,
            $this->productListStorageConfig->getProductListProductAbstractPublishChunkSize()
        );

        foreach ($productAbstractIdsChunks as $productAbstractIdsChunk) {
            $productAbstractProductListStorageEntities = $this->findProductAbstractProductListStorageEntities($productAbstractIdsChunk);
            $indexedProductAbstractProductListStorageEntities = $this->indexProductAbstractProductListStorageEntities($productAbstractProductListStorageEntities);

            $savedProductAbstractProductListStorageEntities = $this->saveProductAbstractProductListStorageEntities(
                $productAbstractIdsChunk,
                $indexedProductAbstractProductListStorageEntities,
                $productLists
            );

            $this->deleteProductAbstractProductListStorageEntitiesWithoutLists(
                $indexedProductAbstractProductListStorageEntities,
                $savedProductAbstractProductListStorageEntities
            );
        }

        if ($isPoolingStateChanged) {
            $this->enableInstancePooling();
        }
    }

    /**
     * @param int[] $productAbstractIds
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage[] $productAbstractProductListStorageEntities
     * @param array $productLists
     *
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage[]
     */
    protected function saveProductAbstractProductListStorageEntities(
        array $productAbstractIds,
        array $productAbstractProductListStorageEntities,
        array $productLists
    ): array {
        $savedProductAbstractProductListStorageEntities = [];

        foreach ($productAbstractIds as $idProductAbstract) {
            $productAbstractProductListsStorageTransfer = $this->getProductAbstractProductListsStorageTransfer(
                $idProductAbstract,
                $productLists
            );

            if (
                !$productAbstractProductListsStorageTransfer->getIdWhitelists()
                && !$productAbstractProductListsStorageTransfer->getIdBlacklists()
            ) {
                continue;
            }

            $productAbstractProductListStorageEntity = $this->getProductAbstractProductListStorageEntity(
                $idProductAbstract,
                $productAbstractProductListStorageEntities
            );

            $productAbstractProductListStorageEntity->setFkProductAbstract($idProductAbstract)
                ->setData($productAbstractProductListsStorageTransfer->toArray())
                ->setIsSendingToQueue($this->productListStorageConfig->isSendingToQueue())
                ->save();

            $savedProductAbstractProductListStorageEntities[$idProductAbstract] = $productAbstractProductListStorageEntity;
        }

        return $savedProductAbstractProductListStorageEntities;
    }

    /**
     * @param int $idProductAbstract
     * @param array $productLists
     *
     * @return \Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer
     */
    protected function getProductAbstractProductListsStorageTransfer(
        int $idProductAbstract,
        array $productLists
    ): ProductAbstractProductListStorageTransfer {
        $productAbstractProductListsStorageTransfer = new ProductAbstractProductListStorageTransfer();
        $productAbstractProductListsStorageTransfer->setIdProductAbstract($idProductAbstract)
            ->setIdBlacklists($this->findProductAbstractBlacklistIds($idProductAbstract, $productLists))
            ->setIdWhitelists($this->findProductAbstractWhitelistIds($idProductAbstract, $productLists));

        return $productAbstractProductListsStorageTransfer;
    }

    /**
     * @param int $idProductAbstract
     * @param array $productLists
     *
     * @return int[]
     */
    protected function findProductAbstractBlacklistIds(int $idProductAbstract, array $productLists): array
    {
        return $productLists[$idProductAbstract][$this->productListStorageRepository->getProductListBlacklistEnumValue()] ?? [];
    }

    /**
     * @param int $idProductAbstract
     * @param array $productLists
     *
     * @return int[]
     */
    protected function findProductAbstractWhitelistIds(int $idProductAbstract, array $productLists): array
    {
        return $productLists[$idProductAbstract][$this->productListStorageRepository->getProductListWhitelistEnumValue()] ?? [];
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage[]
     */
    protected function findProductAbstractProductListStorageEntities(array $productAbstractIds): array
    {
        return $this->productListStorageRepository->findProductAbstractProductListStorageEntities($productAbstractIds);
    }

    /**
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage[] $productAbstractProductListStorageEntities
     *
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage[]
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
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage[] $indexedProductAbstractProductListStorageEntities
     *
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage
     */
    protected function getProductAbstractProductListStorageEntity(
        int $idProductAbstract,
        array $indexedProductAbstractProductListStorageEntities
    ): SpyProductAbstractProductListStorage {
        if (isset($indexedProductAbstractProductListStorageEntities[$idProductAbstract])) {
            return $indexedProductAbstractProductListStorageEntities[$idProductAbstract];
        }

        return new SpyProductAbstractProductListStorage();
    }

    /**
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage[] $productAbstractProductListStorageEntities
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage[] $productAbstractProductListStorageEntitiesWithLists
     *
     * @return void
     */
    protected function deleteProductAbstractProductListStorageEntitiesWithoutLists(
        array $productAbstractProductListStorageEntities,
        array $productAbstractProductListStorageEntitiesWithLists
    ): void {
        $productAbstractProductListStorageEntitiesToDelete = array_diff_key(
            $productAbstractProductListStorageEntities,
            $productAbstractProductListStorageEntitiesWithLists
        );

        foreach ($productAbstractProductListStorageEntitiesToDelete as $productAbstractProductListStorageEntity) {
            $productAbstractProductListStorageEntity->delete();
        }
    }
}
