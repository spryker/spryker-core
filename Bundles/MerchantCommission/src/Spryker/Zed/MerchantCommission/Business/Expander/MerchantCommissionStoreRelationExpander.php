<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface;

class MerchantCommissionStoreRelationExpander implements MerchantCommissionStoreRelationExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface
     */
    protected StoreReaderInterface $storeReader;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface $storeReader
     */
    public function __construct(StoreReaderInterface $storeReader)
    {
        $this->storeReader = $storeReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function expandMerchantCommissionCollectionWithStores(
        MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
    ): MerchantCommissionCollectionTransfer {
        $storeIds = $this->extractStoreIds($merchantCommissionCollectionTransfer->getMerchantCommissions());
        $storeCollectionTransfer = $this->storeReader->getStoreCollectionByStoreIds($storeIds);
        $storeTransfersIndexedByIdStore = $this->getStoreTransferIndexedByIdStore($storeCollectionTransfer->getStores());

        foreach ($merchantCommissionCollectionTransfer->getMerchantCommissions() as $merchantCommissionTransfer) {
            $this->expandStoreRelationWithStoreTransfers(
                $merchantCommissionTransfer->getStoreRelationOrFail(),
                $storeTransfersIndexedByIdStore,
            );
        }

        return $merchantCommissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     * @param array<int, \Generated\Shared\Transfer\StoreTransfer> $storeTransfersIndexedByIdStore
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function expandStoreRelationWithStoreTransfers(
        StoreRelationTransfer $storeRelationTransfer,
        array $storeTransfersIndexedByIdStore
    ): StoreRelationTransfer {
        $storeTransfers = [];
        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            if (!isset($storeTransfersIndexedByIdStore[$storeTransfer->getIdStoreOrFail()])) {
                continue;
            }

            $storeTransfers[] = $storeTransfersIndexedByIdStore[$storeTransfer->getIdStoreOrFail()];
        }

        return $storeRelationTransfer->setStores(new ArrayObject($storeTransfers));
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return list<int>
     */
    protected function extractStoreIds(ArrayObject $merchantCommissionTransfers): array
    {
        $storeIds = [];
        foreach ($merchantCommissionTransfers as $merchantCommissionTransfer) {
            foreach ($merchantCommissionTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
                $storeIds[$storeTransfer->getIdStoreOrFail()] = $storeTransfer->getIdStoreOrFail();
            }
        }

        return array_values($storeIds);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getStoreTransferIndexedByIdStore(ArrayObject $storeTransfers): array
    {
        $indexedStoreTransfers = [];
        foreach ($storeTransfers as $storeTransfer) {
            $indexedStoreTransfers[$storeTransfer->getIdStoreOrFail()] = $storeTransfer;
        }

        return $indexedStoreTransfers;
    }
}
