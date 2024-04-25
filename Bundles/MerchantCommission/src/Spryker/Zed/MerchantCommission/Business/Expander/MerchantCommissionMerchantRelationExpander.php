<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface;

class MerchantCommissionMerchantRelationExpander implements MerchantCommissionMerchantRelationExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface
     */
    protected MerchantReaderInterface $merchantReader;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface $merchantReader
     */
    public function __construct(MerchantReaderInterface $merchantReader)
    {
        $this->merchantReader = $merchantReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function expandMerchantCommissionCollectionWithMerchants(
        MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
    ): MerchantCommissionCollectionTransfer {
        $merchantIds = $this->extractMerchantIds($merchantCommissionCollectionTransfer->getMerchantCommissions());
        $merchantCollectionTransfer = $this->merchantReader->getMerchantCollectionByMerchantIds($merchantIds);
        $merchantTransfersIndexedByIdMerchant = $this->getMerchantTransfersIndexedByIdMerchant(
            $merchantCollectionTransfer->getMerchants(),
        );

        foreach ($merchantCommissionCollectionTransfer->getMerchantCommissions() as $merchantCommissionTransfer) {
            $this->expandMerchantCommissionWithMerchantTransfers(
                $merchantCommissionTransfer,
                $merchantTransfersIndexedByIdMerchant,
            );
        }

        return $merchantCommissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param array<int, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfersIndexedByIdMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    protected function expandMerchantCommissionWithMerchantTransfers(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        array $merchantTransfersIndexedByIdMerchant
    ): MerchantCommissionTransfer {
        $merchantTransfers = [];
        foreach ($merchantCommissionTransfer->getMerchants() as $merchantTransfer) {
            if (!isset($merchantTransfersIndexedByIdMerchant[$merchantTransfer->getIdMerchantOrFail()])) {
                continue;
            }

            $merchantTransfers[] = $merchantTransfersIndexedByIdMerchant[$merchantTransfer->getIdMerchantOrFail()];
        }

        return $merchantCommissionTransfer->setMerchants((new ArrayObject($merchantTransfers)));
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return list<int>
     */
    protected function extractMerchantIds(ArrayObject $merchantCommissionTransfers): array
    {
        $merchantIds = [];
        foreach ($merchantCommissionTransfers as $merchantCommissionTransfer) {
            foreach ($merchantCommissionTransfer->getMerchants() as $merchantTransfer) {
                $merchantIds[$merchantTransfer->getIdMerchantOrFail()] = $merchantTransfer->getIdMerchantOrFail();
            }
        }

        return $merchantIds;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\MerchantTransfer>
     */
    protected function getMerchantTransfersIndexedByIdMerchant(ArrayObject $merchantTransfers): array
    {
        $indexedMerchantTransfers = [];
        foreach ($merchantTransfers as $merchantTransfer) {
            $indexedMerchantTransfers[$merchantTransfer->getIdMerchantOrFail()] = $merchantTransfer;
        }

        return $indexedMerchantTransfers;
    }
}
