<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Updater;

use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface;

class MerchantCommissionMerchantUpdater implements MerchantCommissionMerchantUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface
     */
    protected MerchantReaderInterface $merchantReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface
     */
    protected MerchantCommissionRepositoryInterface $merchantCommissionRepository;

    /**
     * @var \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface
     */
    protected MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface
     */
    protected MerchantDataExtractorInterface $merchantDataExtractor;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface $merchantReader
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface $merchantCommissionRepository
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface $merchantDataExtractor
     */
    public function __construct(
        MerchantReaderInterface $merchantReader,
        MerchantCommissionRepositoryInterface $merchantCommissionRepository,
        MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager,
        MerchantDataExtractorInterface $merchantDataExtractor
    ) {
        $this->merchantReader = $merchantReader;
        $this->merchantCommissionRepository = $merchantCommissionRepository;
        $this->merchantCommissionEntityManager = $merchantCommissionEntityManager;
        $this->merchantDataExtractor = $merchantDataExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function updateMerchantCommissionMerchantRelations(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        $merchantReferences = $this->merchantDataExtractor->extractMerchantReferencesFromMerchantTransfers(
            $merchantCommissionTransfer->getMerchants(),
        );

        $merchantCollectionTransfer = $this->merchantReader->getMerchantCollectionByMerchantReferences($merchantReferences);
        $requestedMerchantIds = $this->merchantDataExtractor->extractMerchantIdsFromMerchantTransfers(
            $merchantCollectionTransfer->getMerchants(),
        );
        $assignedMerchantIds = $this->merchantCommissionRepository->getMerchantIdsRelatedToMerchantCommission(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
        );

        $merchantIdsToAssign = array_diff($requestedMerchantIds, $assignedMerchantIds);
        $merchantIdsToUnAssign = array_diff($assignedMerchantIds, $requestedMerchantIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($merchantCommissionTransfer, $merchantIdsToAssign, $merchantIdsToUnAssign): void {
            $this->executeUpdateMerchantCommissionMerchantRelationsTransaction(
                $merchantCommissionTransfer,
                $merchantIdsToAssign,
                $merchantIdsToUnAssign,
            );
        });

        return $merchantCommissionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param list<int> $merchantIdsToAssign
     * @param list<int> $merchantIdsToUnAssign
     *
     * @return void
     */
    protected function executeUpdateMerchantCommissionMerchantRelationsTransaction(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        array $merchantIdsToAssign,
        array $merchantIdsToUnAssign
    ): void {
        $this->merchantCommissionEntityManager->createMerchantCommissionMerchants(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantIdsToAssign,
        );

        $this->merchantCommissionEntityManager->deleteMerchantCommissionMerchants(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantIdsToUnAssign,
        );
    }
}
