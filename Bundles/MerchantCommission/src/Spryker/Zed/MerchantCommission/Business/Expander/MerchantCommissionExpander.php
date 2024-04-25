<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionGroupDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionAmountReaderInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionGroupReaderInterface;

class MerchantCommissionExpander implements MerchantCommissionExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionAmountReaderInterface
     */
    protected MerchantCommissionAmountReaderInterface $merchantCommissionAmountReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionGroupReaderInterface
     */
    protected MerchantCommissionGroupReaderInterface $merchantCommissionGroupReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionDataExtractorInterface
     */
    protected MerchantCommissionDataExtractorInterface $merchantCommissionDataExtractor;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionGroupDataExtractorInterface
     */
    protected MerchantCommissionGroupDataExtractorInterface $merchantCommissionGroupDataExtractor;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionAmountReaderInterface $merchantCommissionAmountReader
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionGroupReaderInterface $merchantCommissionGroupReader
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionDataExtractorInterface $merchantCommissionDataExtractor
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionGroupDataExtractorInterface $merchantCommissionGroupDataExtractor
     */
    public function __construct(
        MerchantCommissionAmountReaderInterface $merchantCommissionAmountReader,
        MerchantCommissionGroupReaderInterface $merchantCommissionGroupReader,
        MerchantCommissionDataExtractorInterface $merchantCommissionDataExtractor,
        MerchantCommissionGroupDataExtractorInterface $merchantCommissionGroupDataExtractor
    ) {
        $this->merchantCommissionAmountReader = $merchantCommissionAmountReader;
        $this->merchantCommissionGroupReader = $merchantCommissionGroupReader;
        $this->merchantCommissionDataExtractor = $merchantCommissionDataExtractor;
        $this->merchantCommissionGroupDataExtractor = $merchantCommissionGroupDataExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function expandMerchantCommissionCollectionWithMerchantCommissionAmounts(
        MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
    ): MerchantCommissionCollectionTransfer {
        $merchantCommissionIds = $this->merchantCommissionDataExtractor->extractMerchantCommissionIds(
            $merchantCommissionCollectionTransfer->getMerchantCommissions(),
        );
        $merchantCommissionAmountCollectionTransfer = $this->merchantCommissionAmountReader->getMerchantCommissionAmountCollectionByMerchantCommissionIds(
            $merchantCommissionIds,
        );

        $merchantCommissionAmountTransfersGroupedByIdMerchantCommission = $this->getMerchantCommissionAmountTransfersGroupedByIdMerchantCommission(
            $merchantCommissionAmountCollectionTransfer->getMerchantCommissionAmounts(),
        );

        foreach ($merchantCommissionCollectionTransfer->getMerchantCommissions() as $merchantCommissionTransfer) {
            $merchantCommissionAmountTransfers = $merchantCommissionAmountTransfersGroupedByIdMerchantCommission[$merchantCommissionTransfer->getIdMerchantCommissionOrFail()] ?? [];
            $merchantCommissionTransfer->setMerchantCommissionAmounts(
                new ArrayObject($merchantCommissionAmountTransfers),
            );
        }

        return $merchantCommissionCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function expandMerchantCommissionsWithMerchantCommissionGroups(ArrayObject $merchantCommissionTransfers): ArrayObject
    {
        $merchantCommissionGroupUuids = $this->merchantCommissionGroupDataExtractor->extractMerchantCommissionGroupUuidsFromMerchantCommissionTransfers(
            $merchantCommissionTransfers,
        );
        $merchantCommissionGroupCollectionTransfer = $this->merchantCommissionGroupReader->getMerchantCommissionGroupCollectionByUuids(
            $merchantCommissionGroupUuids,
        );
        $merchantCommissionGroupTransfersIndexedByUuid = $this->getMerchantCommissionGroupTransfersIndexedByUuid(
            $merchantCommissionGroupCollectionTransfer->getMerchantCommissionGroups(),
        );

        foreach ($merchantCommissionTransfers as $merchantCommissionTransfer) {
            $merchantCommissionGroupUuid = $merchantCommissionTransfer->getMerchantCommissionGroupOrFail()->getUuidOrFail();
            $merchantCommissionGroupTransfer = $merchantCommissionGroupTransfersIndexedByUuid[$merchantCommissionGroupUuid] ?? null;
            if ($merchantCommissionGroupTransfer) {
                $merchantCommissionTransfer->setMerchantCommissionGroup($merchantCommissionGroupTransfer);
            }
        }

        return $merchantCommissionTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer> $merchantCommissionAmountTransfers
     *
     * @return array<int, list<\Generated\Shared\Transfer\MerchantCommissionAmountTransfer>>
     */
    protected function getMerchantCommissionAmountTransfersGroupedByIdMerchantCommission(ArrayObject $merchantCommissionAmountTransfers): array
    {
        $groupedMerchantCommissionAmounts = [];
        foreach ($merchantCommissionAmountTransfers as $merchantCommissionAmountTransfer) {
            $groupedMerchantCommissionAmounts[$merchantCommissionAmountTransfer->getFkMerchantCommissionOrFail()][] = $merchantCommissionAmountTransfer;
        }

        return $groupedMerchantCommissionAmounts;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionGroupTransfer> $merchantCommissionGroupTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\MerchantCommissionGroupTransfer>
     */
    protected function getMerchantCommissionGroupTransfersIndexedByUuid(ArrayObject $merchantCommissionGroupTransfers): array
    {
        $indexedMerchantCommissionGroupTransfers = [];
        foreach ($merchantCommissionGroupTransfers as $merchantCommissionGroupTransfer) {
            $indexedMerchantCommissionGroupTransfers[$merchantCommissionGroupTransfer->getUuidOrFail()] = $merchantCommissionGroupTransfer;
        }

        return $indexedMerchantCommissionGroupTransfers;
    }
}
