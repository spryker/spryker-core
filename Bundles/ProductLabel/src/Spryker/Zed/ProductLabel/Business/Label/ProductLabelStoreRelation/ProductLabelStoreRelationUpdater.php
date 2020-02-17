<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\ProductLabelStoreRelation;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface;

class ProductLabelStoreRelationUpdater implements ProductLabelStoreRelationUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface
     */
    protected $productLabelRepository;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface
     */
    protected $productLabelEntityManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface $productLabelRepository
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface $productLabelEntityManager
     */
    public function __construct(
        ProductLabelRepositoryInterface $productLabelRepository,
        ProductLabelEntityManagerInterface $productLabelEntityManager
    ) {
        $this->productLabelRepository = $productLabelRepository;
        $this->productLabelEntityManager = $productLabelEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelationTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($storeRelationTransfer) {
            $this->executeUpdateTransaction($storeRelationTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    protected function executeUpdateTransaction(StoreRelationTransfer $storeRelationTransfer): void
    {
        $storeRelationTransfer->requireIdEntity();

        $currentIdStores = $this->getIdStoreByIdProductLabel($storeRelationTransfer->getIdEntity());
        $requestedIdStores = $storeRelationTransfer->getIdStores() ?? [];

        $saveIdStores = array_diff($requestedIdStores, $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $requestedIdStores);

        $this->productLabelEntityManager->addProductLabelStoreRelationForStores($saveIdStores, $storeRelationTransfer->getIdEntity());
        $this->productLabelEntityManager->removeProductLabelStoreRelationForStores($deleteIdStores, $storeRelationTransfer->getIdEntity());
    }

    /**
     * @param int $idProductLabel
     *
     * @return int[]
     */
    protected function getIdStoreByIdProductLabel(int $idProductLabel): array
    {
        $storeRelation = $this->productLabelRepository->getStoreRelationByIdProductLabel($idProductLabel);

        return $storeRelation->getIdStores();
    }
}
