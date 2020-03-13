<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation\Updater;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface;

class ProductRelationStoreRelationUpdater implements ProductRelationStoreRelationUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface
     */
    protected $productRelationRepository;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface
     */
    protected $productRelationEntityManager;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface $productRelationRepository
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface $productRelationEntityManager
     */
    public function __construct(
        ProductRelationRepositoryInterface $productRelationRepository,
        ProductRelationEntityManagerInterface $productRelationEntityManager
    ) {
        $this->productRelationRepository = $productRelationRepository;
        $this->productRelationEntityManager = $productRelationEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelationTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($storeRelationTransfer) {
            $this->executeUpdateStoreRelationTransaction($storeRelationTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    protected function executeUpdateStoreRelationTransaction(StoreRelationTransfer $storeRelationTransfer): void
    {
        $storeRelationTransfer->requireIdEntity();

        $currentIdStores = $this->getIdStoresByIdProductRelation($storeRelationTransfer->getIdEntity());
        $requestedIdStores = $storeRelationTransfer->getIdStores() ?? [];

        $saveIdStores = array_diff($requestedIdStores, $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $requestedIdStores);

        $this->productRelationEntityManager->addProductRelationStoreRelationsForStores($saveIdStores, $storeRelationTransfer->getIdEntity());
        $this->productRelationEntityManager->removeProductRelationStoreRelationsForStores($deleteIdStores, $storeRelationTransfer->getIdEntity());
    }

    /**
     * @param int $idProductRelation
     *
     * @return int[]
     */
    protected function getIdStoresByIdProductRelation(int $idProductRelation): array
    {
        $storeRelation = $this->productRelationRepository->getStoreRelationByIdProductRelation($idProductRelation);

        return $storeRelation->getIdStores();
    }
}
