<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Updater;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DiscountStoreUpdater implements DiscountStoreUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface
     */
    protected $discountRepository;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface
     */
    protected $discountEntityManager;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     * @param \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface $discountEntityManager
     */
    public function __construct(DiscountRepositoryInterface $discountRepository, DiscountEntityManagerInterface $discountEntityManager)
    {
        $this->discountRepository = $discountRepository;
        $this->discountEntityManager = $discountEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return void
     */
    public function updateDiscountStoreRelationships(DiscountConfiguratorTransfer $discountConfiguratorTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($discountConfiguratorTransfer) {
            $this->executeUpdateDiscountStoreRelationshipsTransaction($discountConfiguratorTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return void
     */
    protected function executeUpdateDiscountStoreRelationshipsTransaction(DiscountConfiguratorTransfer $discountConfiguratorTransfer): void
    {
        $idDiscount = $discountConfiguratorTransfer->getDiscountGeneralOrFail()->getIdDiscountOrFail();

        $updatedStoreRelationTransfer = $discountConfiguratorTransfer->getDiscountGeneralOrFail()->getStoreRelationOrFail();
        $existingStoreRelationTransfer = $this->discountRepository->getDiscountStoreRelations($idDiscount);

        $existingStoreIds = $existingStoreRelationTransfer->getIdStores();
        $updatedStoreIds = $updatedStoreRelationTransfer->getIdStores();

        $storeIdsToAdd = array_diff($updatedStoreIds, $existingStoreIds);
        $storeIdsToRemove = array_diff($existingStoreIds, $updatedStoreIds);

        $this->discountEntityManager->createDiscountStoreRelations($idDiscount, $storeIdsToAdd);
        $this->discountEntityManager->deleteDiscountStoreRelations($idDiscount, $storeIdsToRemove);
    }
}
