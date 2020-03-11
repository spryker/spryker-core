<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractRelationStorageTransfer;
use Generated\Shared\Transfer\ProductRelationStorageTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToProductRelationFacadeInterface;
use Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageEntityManagerInterface;
use Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface;

class ProductRelationStorageWriter implements ProductRelationStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface
     */
    protected $productRelationStorageRepository;

    /**
     * @var \Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @var \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageEntityManagerInterface
     */
    protected $productRelationStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface $productRelationStorageRepository
     * @param \Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToProductRelationFacadeInterface $productRelationFacade
     * @param \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageEntityManagerInterface $productRelationStorageEntityManager
     */
    public function __construct(
        ProductRelationStorageRepositoryInterface $productRelationStorageRepository,
        ProductRelationStorageToProductRelationFacadeInterface $productRelationFacade,
        ProductRelationStorageEntityManagerInterface $productRelationStorageEntityManager
    ) {
        $this->productRelationStorageRepository = $productRelationStorageRepository;
        $this->productRelationFacade = $productRelationFacade;
        $this->productRelationStorageEntityManager = $productRelationStorageEntityManager;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $productRelationTransfers = $this->productRelationFacade
            ->getProductRelationsByIdProductAbstracts($productAbstractIds);

        $productRelations = [];
        foreach ($productRelationTransfers as $productRelationTransfer) {
            foreach ($productRelationTransfer->getStoreRelation()->getStores() as $storeTransfer) {
                $productRelations[$productRelationTransfer->getFkProductAbstract()][$storeTransfer->getName()][] = $productRelationTransfer;
            }
        }

        $this->storeData($productRelations);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $this->productRelationStorageEntityManager->deleteProductAbstractRelationStorageEntitiesByProductAbstractIds($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer[][][] $productRelations
     *
     * @return void
     */
    protected function storeData(array $productRelations)
    {
        foreach ($productRelations as $idProduct => $productRelationTransfersByStore) {
            foreach ($productRelationTransfersByStore as $store => $productRelationTransfers) {
                $this->storeDataSet($idProduct, $store, $productRelationTransfers);
            }
        }
    }

    /**
     * @param int $idProductAbstract
     * @param string $store
     * @param \Generated\Shared\Transfer\ProductRelationTransfer[] $productRelations
     *
     * @return void
     */
    protected function storeDataSet(
        int $idProductAbstract,
        string $store,
        array $productRelations
    ) {
        $productRelationStorageTransfers = $this->fillProductRelationStorageTransfers($productRelations, $store);

        $productAbstractRelationStorageTransfer = new ProductAbstractRelationStorageTransfer();
        $productAbstractRelationStorageTransfer->setIdProductAbstract($idProductAbstract);
        $productAbstractRelationStorageTransfer->setProductRelations($productRelationStorageTransfers);
        $productAbstractRelationStorageTransfer->setStore($store);

        $this->productRelationStorageEntityManager->saveProductAbstractRelationStorageEntity(
            $idProductAbstract,
            $productAbstractRelationStorageTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer[] $productRelations
     * @param string $store
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductRelationStorageTransfer[]
     */
    protected function fillProductRelationStorageTransfers(array $productRelations, string $store)
    {
        $productRelationStorageTransfers = new ArrayObject();

        foreach ($productRelations as $productRelationTransfer) {
            $storeNames = $this->extractStoreNamesFromStoreRelation($productRelationTransfer->getStoreRelation());

            if (!in_array($store, $storeNames)) {
                continue;
            }

            $productRelationStorageTransfer = new ProductRelationStorageTransfer();
            $productRelationStorageTransfer->setIsActive($productRelationTransfer->getIsActive());
            $productRelationStorageTransfer->setKey($productRelationTransfer->getProductRelationType()->getKey());
            $productRelationStorageTransfer->setProductAbstractIds($this->fillProductAbstractIds($productRelationTransfer));
            $productRelationStorageTransfers->append($productRelationStorageTransfer);
        }

        return $productRelationStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int[]
     */
    protected function fillProductAbstractIds(
        ProductRelationTransfer $productRelationTransfer
    ): array {
        $productAbstractIds = [];

        foreach ($productRelationTransfer->getRelatedProducts() as $productRelationRelatedProductTransfer) {
            $productAbstractIds[$productRelationRelatedProductTransfer->getFkProductAbstract()] = $productRelationRelatedProductTransfer->getOrder();
        }

        return $productAbstractIds;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return string[]
     */
    protected function extractStoreNamesFromStoreRelation(
        StoreRelationTransfer $storeRelationTransfer
    ): array {
        $storeNames = [];

        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeNames[] = $storeTransfer->getName();
        }

        return $storeNames;
    }
}
