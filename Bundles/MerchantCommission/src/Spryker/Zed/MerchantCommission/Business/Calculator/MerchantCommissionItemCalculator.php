<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Calculator;

use Generated\Shared\Transfer\CollectedMerchantCommissionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\MerchantCommission\Business\Collector\CommissionableItemCollectorInterface;
use Spryker\Zed\MerchantCommission\Business\Resolver\MerchantCommissionCalculatorPluginResolverInterface;
use Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface;

class MerchantCommissionItemCalculator implements MerchantCommissionItemCalculatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Collector\CommissionableItemCollectorInterface
     */
    protected CommissionableItemCollectorInterface $commissionableItemCollector;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Resolver\MerchantCommissionCalculatorPluginResolverInterface
     */
    protected MerchantCommissionCalculatorPluginResolverInterface $merchantCommissionCalculatorPluginResolver;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Collector\CommissionableItemCollectorInterface $commissionableItemCollector
     * @param \Spryker\Zed\MerchantCommission\Business\Resolver\MerchantCommissionCalculatorPluginResolverInterface $merchantCommissionCalculatorPluginResolver
     */
    public function __construct(
        CommissionableItemCollectorInterface $commissionableItemCollector,
        MerchantCommissionCalculatorPluginResolverInterface $merchantCommissionCalculatorPluginResolver
    ) {
        $this->commissionableItemCollector = $commissionableItemCollector;
        $this->merchantCommissionCalculatorPluginResolver = $merchantCommissionCalculatorPluginResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param list<\Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer>
     */
    public function calculateMerchantCommissionForItems(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        array $merchantCommissionTransfers
    ): array {
        $collectedMerchantCommissionTransfers = $this->commissionableItemCollector->collectCommissionableItems(
            $merchantCommissionCalculationRequestTransfer,
            $merchantCommissionTransfers,
        );

        $merchantCommissionCalculationItems = [];
        foreach ($collectedMerchantCommissionTransfers as $collectedMerchantCommissionTransfer) {
            $merchantCommissionTransfer = $collectedMerchantCommissionTransfer->getMerchantCommissionOrFail();
            $merchantCommissionCalculatorPlugin = $this->merchantCommissionCalculatorPluginResolver->getMerchantCommissionCalculatorPlugin(
                $collectedMerchantCommissionTransfer->getMerchantCommissionOrFail()->getCalculatorTypePluginOrFail(),
            );

            $merchantCommissionCalculationItems = $this->calculateCommissionForItemsWithPlugin(
                $collectedMerchantCommissionTransfer,
                $merchantCommissionTransfer,
                $merchantCommissionCalculatorPlugin,
                $merchantCommissionCalculationRequestTransfer,
                $merchantCommissionCalculationItems,
            );
        }

        return $merchantCommissionCalculationItems;
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer $collectedMerchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface $merchantCommissionCalculatorPlugin
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param array<int, \Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer> $merchantCommissionCalculationItems
     *
     * @return array<int, \Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer>
     */
    protected function calculateCommissionForItemsWithPlugin(
        CollectedMerchantCommissionTransfer $collectedMerchantCommissionTransfer,
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculatorPluginInterface $merchantCommissionCalculatorPlugin,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        array $merchantCommissionCalculationItems
    ): array {
        foreach ($collectedMerchantCommissionTransfer->getCommissionableItems() as $merchantCommissionCalculationRequestItemTransfer) {
            $calculatedMerchantCommissionAmount = $merchantCommissionCalculatorPlugin->calculateMerchantCommission(
                $merchantCommissionTransfer,
                $merchantCommissionCalculationRequestItemTransfer,
                $merchantCommissionCalculationRequestTransfer,
            );

            if ($calculatedMerchantCommissionAmount <= 0) {
                continue;
            }

            $idSalesOrderItem = $merchantCommissionCalculationRequestItemTransfer->getIdSalesOrderItemOrFail();
            $merchantCommissionCalculationItemTransfer = $merchantCommissionCalculationItems[$idSalesOrderItem] ?? (new MerchantCommissionCalculationItemTransfer())
                ->fromArray($merchantCommissionCalculationRequestItemTransfer->toArray(), true)
                ->setMerchantCommissionAmountAggregation(0)
                ->setMerchantCommissionAmountFullAggregation(0);

            $merchantCommissionCalculationItemTransfer = $this->addCalculatedMerchantCommissionToMerchantCommissionCalculationItem(
                $merchantCommissionCalculationItemTransfer,
                $merchantCommissionTransfer,
                $calculatedMerchantCommissionAmount,
            );

            $merchantCommissionCalculationItems[$idSalesOrderItem] = $merchantCommissionCalculationItemTransfer;
        }

        return $merchantCommissionCalculationItems;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer $merchantCommissionCalculationItemTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param int $calculatedMerchantCommissionAmount
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer
     */
    protected function addCalculatedMerchantCommissionToMerchantCommissionCalculationItem(
        MerchantCommissionCalculationItemTransfer $merchantCommissionCalculationItemTransfer,
        MerchantCommissionTransfer $merchantCommissionTransfer,
        int $calculatedMerchantCommissionAmount
    ): MerchantCommissionCalculationItemTransfer {
        $merchantCommissionCalculationItemTransfer->setMerchantCommissionAmountAggregation(
            $merchantCommissionCalculationItemTransfer->getMerchantCommissionAmountAggregationOrFail() + $calculatedMerchantCommissionAmount,
        );
        $merchantCommissionCalculationItemTransfer->setMerchantCommissionAmountFullAggregation(
            $merchantCommissionCalculationItemTransfer->getMerchantCommissionAmountFullAggregationOrFail() + $calculatedMerchantCommissionAmount,
        );

        return $merchantCommissionCalculationItemTransfer->addMerchantCommission(
            (new MerchantCommissionTransfer())
                ->fromArray($merchantCommissionTransfer->toArray(), true)
                ->setAmount($calculatedMerchantCommissionAmount),
        );
    }
}
