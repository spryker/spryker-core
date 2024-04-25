<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Creator;

use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantCommission\Business\Extractor\StoreDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface;

class MerchantCommissionStoreCreator implements MerchantCommissionStoreCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface
     */
    protected StoreReaderInterface $storeReader;

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
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\StoreDataExtractorInterface $storeDataExtractor
     */
    public function __construct(
        StoreReaderInterface $storeReader,
        MerchantCommissionEntityManagerInterface $merchantCommissionEntityManager,
        StoreDataExtractorInterface $storeDataExtractor
    ) {
        $this->storeReader = $storeReader;
        $this->merchantCommissionEntityManager = $merchantCommissionEntityManager;
        $this->storeDataExtractor = $storeDataExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommissionStoreRelations(MerchantCommissionTransfer $merchantCommissionTransfer): MerchantCommissionTransfer
    {
        $storeRelationTransfer = $merchantCommissionTransfer->getStoreRelationOrFail();
        $storeNames = $this->storeDataExtractor->extractStoreNamesFromStoreTransfers($storeRelationTransfer->getStores());
        $storeCollectionTransfer = $this->storeReader->getStoreCollectionByStoreNames($storeNames);
        $storeIds = $this->storeDataExtractor->extractStoreIdsFromStoreTransfers($storeCollectionTransfer->getStores());

        $this->getTransactionHandler()->handleTransaction(function () use ($merchantCommissionTransfer, $storeIds): void {
            $this->executeCreateMerchantCommissionStoreRelationsTransaction(
                $merchantCommissionTransfer,
                $storeIds,
            );
        });

        return $merchantCommissionTransfer->setStoreRelation(
            $storeRelationTransfer
                ->setIdEntity($merchantCommissionTransfer->getIdMerchantCommissionOrFail())
                ->setStores($storeCollectionTransfer->getStores()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param list<int> $storeIds
     *
     * @return void
     */
    protected function executeCreateMerchantCommissionStoreRelationsTransaction(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        array $storeIds
    ): void {
        $this->merchantCommissionEntityManager->createMerchantCommissionStores(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $storeIds,
        );
    }
}
