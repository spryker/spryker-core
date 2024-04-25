<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Updater;

use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantCommission\Business\Extractor\StoreDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface;

class MerchantCommissionStoreUpdater implements MerchantCommissionStoreUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface
     */
    protected StoreReaderInterface $storeReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface
     */
    protected MerchantCommissionRepositoryInterface $merchantCommissionRepository;

    /**
     * @var \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface
     */
    protected MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\StoreDataExtractorInterface
     */
    protected StoreDataExtractorInterface $storeDataExtractor;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface $storeReader
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface $merchantCommissionRepository
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\StoreDataExtractorInterface $storeDataExtractor
     */
    public function __construct(
        StoreReaderInterface $storeReader,
        MerchantCommissionRepositoryInterface $merchantCommissionRepository,
        MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager,
        StoreDataExtractorInterface $storeDataExtractor
    ) {
        $this->storeReader = $storeReader;
        $this->merchantCommissionRepository = $merchantCommissionRepository;
        $this->merchantCommissionEntityManager = $merchantCommissionEntityManager;
        $this->storeDataExtractor = $storeDataExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function updateMerchantCommissionStoreRelations(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        $storeRelationTransfer = $merchantCommissionTransfer->getStoreRelationOrFail();
        $storeNames = $this->storeDataExtractor->extractStoreNamesFromStoreTransfers($storeRelationTransfer->getStores());

        $storeCollectionTransfer = $this->storeReader->getStoreCollectionByStoreNames($storeNames);
        $requestedStoreIds = $this->storeDataExtractor->extractStoreIdsFromStoreTransfers($storeCollectionTransfer->getStores());
        $assignedStoreIds = $this->merchantCommissionRepository->getStoreIdsRelatedToMerchantCommission(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
        );

        $storeIdsToAssign = array_diff($requestedStoreIds, $assignedStoreIds);
        $storeIdsToUnAssign = array_diff($assignedStoreIds, $requestedStoreIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($merchantCommissionTransfer, $storeIdsToAssign, $storeIdsToUnAssign): void {
            $this->executeUpdateMerchantCommissionStoreRelationsTransaction($merchantCommissionTransfer, $storeIdsToAssign, $storeIdsToUnAssign);
        });

        return $merchantCommissionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param list<int> $storeIdsToAssign
     * @param list<int> $storeIdsToUnAssign
     *
     * @return void
     */
    protected function executeUpdateMerchantCommissionStoreRelationsTransaction(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        array $storeIdsToAssign,
        array $storeIdsToUnAssign
    ): void {
        $this->merchantCommissionEntityManager->createMerchantCommissionStores(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $storeIdsToAssign,
        );

        $this->merchantCommissionEntityManager->deleteMerchantCommissionStores(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $storeIdsToUnAssign,
        );
    }
}
