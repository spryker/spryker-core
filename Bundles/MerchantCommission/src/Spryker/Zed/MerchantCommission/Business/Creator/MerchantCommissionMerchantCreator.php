<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Creator;

use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface;

class MerchantCommissionMerchantCreator implements MerchantCommissionMerchantCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface
     */
    protected MerchantReaderInterface $merchantReader;

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
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface $merchantDataExtractor
     */
    public function __construct(
        MerchantReaderInterface $merchantReader,
        MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager,
        MerchantDataExtractorInterface $merchantDataExtractor
    ) {
        $this->merchantReader = $merchantReader;
        $this->merchantCommissionEntityManager = $merchantCommissionEntityManager;
        $this->merchantDataExtractor = $merchantDataExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommissionMerchantRelations(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        $merchantReferences = $this->merchantDataExtractor->extractMerchantReferencesFromMerchantTransfers(
            $merchantCommissionTransfer->getMerchants(),
        );
        $merchantCollectionTransfer = $this->merchantReader->getMerchantCollectionByMerchantReferences($merchantReferences);
        $merchantIds = $this->merchantDataExtractor->extractMerchantIdsFromMerchantTransfers(
            $merchantCollectionTransfer->getMerchants(),
        );

        $this->getTransactionHandler()->handleTransaction(function () use ($merchantCommissionTransfer, $merchantIds): void {
            $this->executeCreateMerchantCommissionMerchantRelationsTransaction(
                $merchantCommissionTransfer,
                $merchantIds,
            );
        });

        return $merchantCommissionTransfer->setMerchants($merchantCollectionTransfer->getMerchants());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param list<int> $merchantIds
     *
     * @return void
     */
    protected function executeCreateMerchantCommissionMerchantRelationsTransaction(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        array $merchantIds
    ): void {
        $this->merchantCommissionEntityManager->createMerchantCommissionMerchants(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantIds,
        );
    }
}
