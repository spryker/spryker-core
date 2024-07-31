<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Adder;

use Generated\Shared\Transfer\CollectedMerchantCommissionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface;
use Spryker\Zed\MerchantCommission\MerchantCommissionConfig;

class MerchantCommissionAdder implements MerchantCommissionAdderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface
     */
    protected MerchantDataExtractorInterface $merchantDataExtractor;

    /**
     * @var \Spryker\Zed\MerchantCommission\MerchantCommissionConfig
     */
    protected MerchantCommissionConfig $merchantCommissionConfig;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface $merchantDataExtractor
     * @param \Spryker\Zed\MerchantCommission\MerchantCommissionConfig $merchantCommissionConfig
     */
    public function __construct(
        MerchantDataExtractorInterface $merchantDataExtractor,
        MerchantCommissionConfig $merchantCommissionConfig
    ) {
        $this->merchantDataExtractor = $merchantDataExtractor;
        $this->merchantCommissionConfig = $merchantCommissionConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $collectedMerchantCommissionCalculationRequestItems
     * @param array<int, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer> $collectedMerchantCommissionTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>
     */
    public function addCommissionableItemsToCollectedMerchantCommissions(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        array $collectedMerchantCommissionCalculationRequestItems,
        array $collectedMerchantCommissionTransfers
    ): array {
        $merchantReferences = $this->merchantDataExtractor->extractMerchantReferencesFromMerchantTransfers(
            $merchantCommissionTransfer->getMerchants(),
        );
        foreach ($collectedMerchantCommissionCalculationRequestItems as $merchantCommissionCalculationRequestItem) {
            if (!$merchantCommissionCalculationRequestItem->getMerchantReference()) {
                continue;
            }

            if (!$this->isAllowedMerchantItem($merchantCommissionCalculationRequestItem, $merchantReferences)) {
                continue;
            }

            $idSalesOrderItem = $merchantCommissionCalculationRequestItem->getIdSalesOrderItemOrFail();
            if (isset($collectedMerchantCommissionTransfers[$idSalesOrderItem])) {
                continue;
            }

            $collectedMerchantCommissionTransfers[$idSalesOrderItem] = (new CollectedMerchantCommissionTransfer())
                ->addCommissionableItem($merchantCommissionCalculationRequestItem)
                ->setMerchantCommission($merchantCommissionTransfer);
        }

        return $collectedMerchantCommissionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer
     * @param list<string> $merchantReferences
     *
     * @return bool
     */
    protected function isAllowedMerchantItem(
        MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer,
        array $merchantReferences
    ): bool {
        return $this->isMerchantItemInAllowedMerchantList($merchantCommissionCalculationRequestItemTransfer, $merchantReferences)
            && !$this->isExcludedMerchantItem($merchantCommissionCalculationRequestItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer
     * @param list<string> $merchantReferences
     *
     * @return bool
     */
    protected function isMerchantItemInAllowedMerchantList(
        MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer,
        array $merchantReferences
    ): bool {
        return $merchantReferences === [] || in_array(
            $merchantCommissionCalculationRequestItemTransfer->getMerchantReferenceOrFail(),
            $merchantReferences,
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer
     *
     * @return bool
     */
    protected function isExcludedMerchantItem(
        MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer
    ): bool {
        return in_array(
            $merchantCommissionCalculationRequestItemTransfer->getMerchantReferenceOrFail(),
            $this->merchantCommissionConfig->getExcludedMerchantsFromCommission(),
            true,
        );
    }
}
