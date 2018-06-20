<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductListProductConcreteStorage;

use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
use Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer;
use Spryker\Zed\ProductListStorage\Business\ProductAbstract\ProductAbstractReaderInterface;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface;
use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageEntityManagerInterface;
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
     * @var \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageEntityManagerInterface
     */
    protected $productListStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductListStorage\Business\ProductAbstract\ProductAbstractReaderInterface
     */
    protected $productAbstractReader;

    /**
     * @param \Spryker\Zed\ProductListStorage\Business\ProductAbstract\ProductAbstractReaderInterface $productAbstractReader
     * @param \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface $productListFacade
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface $productListStorageRepository
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageEntityManagerInterface $productListStorageEntityManager
     */
    public function __construct(
        ProductAbstractReaderInterface $productAbstractReader,
        ProductListStorageToProductListFacadeInterface $productListFacade,
        ProductListStorageRepositoryInterface $productListStorageRepository,
        ProductListStorageEntityManagerInterface $productListStorageEntityManager
    ) {

        $this->productListFacade = $productListFacade;
        $this->productListStorageRepository = $productListStorageRepository;
        $this->productListStorageEntityManager = $productListStorageEntityManager;
        $this->productAbstractReader = $productAbstractReader;
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
        foreach ($productConcreteIds as $idProductConcrete) {
            $productConcreteProductListStorageEntity = $this->getProductConcreteProductListStorageEntity($idProductConcrete, $indexedProductConcreteProductListStorageEntities);
            unset($indexedProductConcreteProductListStorageEntities[$idProductConcrete]);

            $this->saveProductConcreteProductListStorageEntity($idProductConcrete, $productConcreteProductListStorageEntity);
        }

        $this->deleteProductConcreteProductListStorageEntities($indexedProductConcreteProductListStorageEntities);
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer $productConcreteProductListStorageEntity
     *
     * @return void
     */
    protected function saveProductConcreteProductListStorageEntity(
        int $idProductConcrete,
        SpyProductConcreteProductListStorageEntityTransfer $productConcreteProductListStorageEntity
    ): void {
        $productConcreteProductListsStorageTransfer = $this->getProductConcreteProductListsStorageTransfer($idProductConcrete);
        if ($productConcreteProductListsStorageTransfer->getIdBlacklists() || $productConcreteProductListsStorageTransfer->getIdWhitelists()) {
            $productConcreteProductListStorageEntity->setFkProduct($idProductConcrete)
                ->setData($productConcreteProductListsStorageTransfer->toArray());

            $this->productListStorageEntityManager->saveProductConcreteProductListStorage($productConcreteProductListStorageEntity);
        }
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer
     */
    protected function getProductConcreteProductListsStorageTransfer(int $idProductConcrete): ProductConcreteProductListStorageTransfer
    {
        $productConcreteProductListsStorageTransfer = new ProductConcreteProductListStorageTransfer();
        $productConcreteProductListsStorageTransfer->setIdProductConcrete($idProductConcrete)
            ->setIdBlacklists($this->findProductConcreteBlacklistIdsByIdProductConcrete($idProductConcrete))
            ->setIdWhitelists($this->findProductConcreteWhitelistIdsByIdProductConcrete($idProductConcrete));

        return $productConcreteProductListsStorageTransfer;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    protected function findProductConcreteBlacklistIdsByIdProductConcrete(int $idProductConcrete): array
    {
        $blacklistIds = [];
        $productAbstractIds = $this->productAbstractReader->findProductAbstractIdsByProductConcreteIds([$idProductConcrete]);

        foreach ($productAbstractIds as $idProductAbstract) {
            $blacklistIds = array_merge(
                $blacklistIds,
                $this->productListFacade->getProductAbstractBlacklistIdsIdProductAbstract($idProductAbstract)
            );
        }

        return array_unique($blacklistIds);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    protected function findProductConcreteWhitelistIdsByIdProductConcrete(int $idProductConcrete): array
    {
        $whitelistIds = [];
        $productAbstractIds = $this->productAbstractReader->findProductAbstractIdsByProductConcreteIds([$idProductConcrete]);

        foreach ($productAbstractIds as $idProductAbstract) {
            $whitelistIds = array_merge(
                $whitelistIds,
                $this->productListFacade->getProductAbstractWhitelistIdsByIdProductAbstract($idProductAbstract)
            );
        }

        return array_unique($whitelistIds);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer[]
     */
    protected function findProductConcreteProductListStorageEntities(array $productConcreteIds): array
    {
        return $this->productListStorageRepository->findProductConcreteProductListStorageEntities($productConcreteIds);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer[] $productConcreteProductListStorageEntities
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer[]
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
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer[] $indexedProductConcreteProductListStorageEntities
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer
     */
    protected function getProductConcreteProductListStorageEntity(int $idProductConcrete, array $indexedProductConcreteProductListStorageEntities): SpyProductConcreteProductListStorageEntityTransfer
    {
        if (isset($indexedProductConcreteProductListStorageEntities[$idProductConcrete])) {
            return $indexedProductConcreteProductListStorageEntities[$idProductConcrete];
        }

        return new SpyProductConcreteProductListStorageEntityTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer[] $productConcreteProductListStorageEntities
     *
     * @return void
     */
    protected function deleteProductConcreteProductListStorageEntities(array $productConcreteProductListStorageEntities): void
    {
        foreach ($productConcreteProductListStorageEntities as $productConcreteProductListStorageEntity) {
            $this->productListStorageEntityManager->deleteProductConcreteProductListStorage($productConcreteProductListStorageEntity->getIdProductProductListStorage());
        }
    }
}
