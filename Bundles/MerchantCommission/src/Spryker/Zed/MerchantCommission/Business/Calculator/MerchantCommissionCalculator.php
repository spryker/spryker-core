<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationTotalsTransfer;
use Spryker\Zed\MerchantCommission\Business\Filter\MerchantCommissionFilterInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionReaderInterface;
use Spryker\Zed\MerchantCommission\MerchantCommissionConfig;

class MerchantCommissionCalculator implements MerchantCommissionCalculatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionReaderInterface
     */
    protected MerchantCommissionReaderInterface $merchantCommissionReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Filter\MerchantCommissionFilterInterface
     */
    protected MerchantCommissionFilterInterface $merchantCommissionFilter;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Calculator\MerchantCommissionItemCalculatorInterface
     */
    protected MerchantCommissionItemCalculatorInterface $merchantCommissionItemCalculator;

    /**
     * @var \Spryker\Zed\MerchantCommission\MerchantCommissionConfig
     */
    protected MerchantCommissionConfig $merchantCommissionConfig;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionReaderInterface $merchantCommissionReader
     * @param \Spryker\Zed\MerchantCommission\Business\Filter\MerchantCommissionFilterInterface $merchantCommissionFilter
     * @param \Spryker\Zed\MerchantCommission\Business\Calculator\MerchantCommissionItemCalculatorInterface $merchantCommissionItemCalculator
     * @param \Spryker\Zed\MerchantCommission\MerchantCommissionConfig $merchantCommissionConfig
     */
    public function __construct(
        MerchantCommissionReaderInterface $merchantCommissionReader,
        MerchantCommissionFilterInterface $merchantCommissionFilter,
        MerchantCommissionItemCalculatorInterface $merchantCommissionItemCalculator,
        MerchantCommissionConfig $merchantCommissionConfig
    ) {
        $this->merchantCommissionReader = $merchantCommissionReader;
        $this->merchantCommissionFilter = $merchantCommissionFilter;
        $this->merchantCommissionItemCalculator = $merchantCommissionItemCalculator;
        $this->merchantCommissionConfig = $merchantCommissionConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer
     */
    public function calculateMerchantCommission(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): MerchantCommissionCalculationResponseTransfer {
        $merchantCommissionCalculationResponseTransfer = $this->createMerchantCommissionCalculationResponseTransfer(
            $merchantCommissionCalculationRequestTransfer,
        );

        $merchantCommissionCalculationRequestItemsGroupedByMerchantReference = $this->getMerchantCommissionCalculationRequestItemsGroupedByMerchantReference(
            $merchantCommissionCalculationRequestTransfer->getItems(),
        );
        if (!$this->hasApplicableItems($merchantCommissionCalculationRequestItemsGroupedByMerchantReference)) {
            return $merchantCommissionCalculationResponseTransfer;
        }

        $merchantCommissionCollectionTransfer = $this->merchantCommissionReader->getActiveMerchantCommissionCollectionForStore(
            $merchantCommissionCalculationRequestTransfer->getStoreOrFail(),
        );

        $applicableMerchantCommissionTransfers = $this->merchantCommissionFilter->filterOutNotApplicableMerchantCommissions(
            $merchantCommissionCollectionTransfer,
            $merchantCommissionCalculationRequestTransfer,
            $merchantCommissionCalculationRequestItemsGroupedByMerchantReference,
        );

        $merchantCommissionCalculationItemTransfers = $this->merchantCommissionItemCalculator->calculateMerchantCommissionForItems(
            $merchantCommissionCalculationRequestTransfer,
            $applicableMerchantCommissionTransfers,
        );
        $merchantCommissionCalculationResponseTransfer->setItems(new ArrayObject($merchantCommissionCalculationItemTransfers));

        return $this->calculateMerchantCommissionAmountTotals($merchantCommissionCalculationResponseTransfer);
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>> $merchantCommissionCalculationRequestItemsGroupedByMerchantReference
     *
     * @return bool
     */
    protected function hasApplicableItems(array $merchantCommissionCalculationRequestItemsGroupedByMerchantReference): bool
    {
        $merchantReferences = array_keys($merchantCommissionCalculationRequestItemsGroupedByMerchantReference);

        return array_diff($merchantReferences, $this->merchantCommissionConfig->getExcludedMerchantsFromCommission()) !== [];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer $merchantCommissionCalculationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer
     */
    protected function calculateMerchantCommissionAmountTotals(
        MerchantCommissionCalculationResponseTransfer $merchantCommissionCalculationResponseTransfer
    ): MerchantCommissionCalculationResponseTransfer {
        $totalCalculatedAmount = 0;
        foreach ($merchantCommissionCalculationResponseTransfer->getItems() as $merchantCommissionCalculationItemTransfer) {
            $totalCalculatedAmount += $merchantCommissionCalculationItemTransfer->getMerchantCommissionAmountFullAggregationOrFail();
        }

        $merchantCommissionCalculationResponseTransfer->getTotalsOrFail()
            ->setMerchantCommissionTotal($totalCalculatedAmount);

        return $merchantCommissionCalculationResponseTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $merchantCommissionCalculationRequestItemTransfers
     *
     * @return array<string, list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>>
     */
    protected function getMerchantCommissionCalculationRequestItemsGroupedByMerchantReference(
        ArrayObject $merchantCommissionCalculationRequestItemTransfers
    ): array {
        $groupedMerchantCommissionCalculationRequestItemTransfers = [];
        foreach ($merchantCommissionCalculationRequestItemTransfers as $merchantCommissionCalculationRequestItemTransfer) {
            $merchantReference = $merchantCommissionCalculationRequestItemTransfer->getMerchantReference();
            if ($merchantReference === null) {
                continue;
            }

            $groupedMerchantCommissionCalculationRequestItemTransfers[$merchantReference][] = $merchantCommissionCalculationRequestItemTransfer;
        }

        return $groupedMerchantCommissionCalculationRequestItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer
     */
    protected function createMerchantCommissionCalculationResponseTransfer(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): MerchantCommissionCalculationResponseTransfer {
        $merchantCommissionCalculationTotalsTransfer = (new MerchantCommissionCalculationTotalsTransfer())
            ->setIdSalesOrder($merchantCommissionCalculationRequestTransfer->getIdSalesOrderOrFail())
            ->setMerchantCommissionTotal(0);

        return (new MerchantCommissionCalculationResponseTransfer())
            ->setTotals($merchantCommissionCalculationTotalsTransfer);
    }
}
