<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Collector;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Zed\MerchantCommission\Business\Adder\MerchantCommissionAdderInterface;
use Spryker\Zed\MerchantCommission\Business\Grouper\MerchantCommissionGrouperInterface;
use Spryker\Zed\MerchantCommission\Business\Merger\MerchantCommissionMergerInterface;
use Spryker\Zed\MerchantCommission\Business\Sorter\MerchantCommissionSorterInterface;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface;
use Spryker\Zed\MerchantCommission\MerchantCommissionConfig;

class CommissionableItemCollector implements CommissionableItemCollectorInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Sorter\MerchantCommissionSorterInterface
     */
    protected MerchantCommissionSorterInterface $merchantCommissionSorter;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Grouper\MerchantCommissionGrouperInterface
     */
    protected MerchantCommissionGrouperInterface $merchantCommissionGrouper;

    /**
     * @var \Spryker\Zed\MerchantCommission\MerchantCommissionConfig
     */
    protected MerchantCommissionConfig $merchantCommissionConfig;

    /**
     * @var \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface
     */
    protected MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Merger\MerchantCommissionMergerInterface
     */
    protected MerchantCommissionMergerInterface $merchantCommissionMerger;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Adder\MerchantCommissionAdderInterface
     */
    protected MerchantCommissionAdderInterface $merchantCommissionAdder;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Sorter\MerchantCommissionSorterInterface $merchantCommissionSorter
     * @param \Spryker\Zed\MerchantCommission\Business\Grouper\MerchantCommissionGrouperInterface $merchantCommissionGrouper
     * @param \Spryker\Zed\MerchantCommission\MerchantCommissionConfig $merchantCommissionConfig
     * @param \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade
     * @param \Spryker\Zed\MerchantCommission\Business\Merger\MerchantCommissionMergerInterface $merchantCommissionMerger
     * @param \Spryker\Zed\MerchantCommission\Business\Adder\MerchantCommissionAdderInterface $merchantCommissionAdder
     */
    public function __construct(
        MerchantCommissionSorterInterface $merchantCommissionSorter,
        MerchantCommissionGrouperInterface $merchantCommissionGrouper,
        MerchantCommissionConfig $merchantCommissionConfig,
        MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade,
        MerchantCommissionMergerInterface $merchantCommissionMerger,
        MerchantCommissionAdderInterface $merchantCommissionAdder
    ) {
        $this->merchantCommissionSorter = $merchantCommissionSorter;
        $this->merchantCommissionGrouper = $merchantCommissionGrouper;
        $this->merchantCommissionConfig = $merchantCommissionConfig;
        $this->ruleEngineFacade = $ruleEngineFacade;
        $this->merchantCommissionMerger = $merchantCommissionMerger;
        $this->merchantCommissionAdder = $merchantCommissionAdder;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param list<\Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>
     */
    public function collectCommissionableItems(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        array $merchantCommissionTransfers
    ): array {
        $merchantCommissionTransfersGroupedByGroupKey = $this->merchantCommissionGrouper->getMerchantCommissionsGroupedByMerchantCommissionGroupKey(
            $merchantCommissionTransfers,
        );

        $collectedMerchantCommissionTransfers = [];
        foreach ($merchantCommissionTransfersGroupedByGroupKey as $groupKey => $merchantCommissionTransfers) {
            $sortedMerchantCommissionTransfers = $this->merchantCommissionSorter->sortMerchantCommissionsByPriority(
                $merchantCommissionTransfers,
            );

            $collectedMerchantCommissionTransfers[$groupKey] = $this->collectCommissionableItemsForMerchantCommissionGroup(
                $merchantCommissionCalculationRequestTransfer,
                $sortedMerchantCommissionTransfers,
            );
        }

        return $this->merchantCommissionMerger->mergeCollectedMerchantCommissions($collectedMerchantCommissionTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param array<\Generated\Shared\Transfer\MerchantCommissionTransfer> $sortedMerchantCommissionTransfers
     *
     * @return list<\Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>
     */
    protected function collectCommissionableItemsForMerchantCommissionGroup(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        array $sortedMerchantCommissionTransfers
    ): array {
        $merchantCommissionCalculationRequestItemsCount = $merchantCommissionCalculationRequestTransfer->getItems()->count();
        $collectedMerchantCommissionTransfers = [];
        foreach ($sortedMerchantCommissionTransfers as $merchantCommissionTransfer) {
            $collectedMerchantCommissionTransfers = $this->collectCommissionableItemsForMerchantCommission(
                $merchantCommissionTransfer,
                $merchantCommissionCalculationRequestTransfer,
                $collectedMerchantCommissionTransfers,
            );

            if (count($collectedMerchantCommissionTransfers) === $merchantCommissionCalculationRequestItemsCount) {
                break;
            }
        }

        return $collectedMerchantCommissionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param array<int, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer> $collectedMerchantCommissionTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>
     */
    protected function collectCommissionableItemsForMerchantCommission(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        array $collectedMerchantCommissionTransfers
    ): array {
        if (!$merchantCommissionTransfer->getItemCondition()) {
            return $this->merchantCommissionAdder->addCommissionableItemsToCollectedMerchantCommissions(
                $merchantCommissionTransfer,
                $merchantCommissionCalculationRequestTransfer->getItems()->getArrayCopy(),
                $collectedMerchantCommissionTransfers,
            );
        }

        $ruleEngineSpecificationProviderRequestTransfer = (new RuleEngineSpecificationProviderRequestTransfer())
            ->setDomainName($this->merchantCommissionConfig->getRuleEngineMerchantCommissionDomainName());
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestTransfer())
            ->setRuleEngineSpecificationProviderRequest($ruleEngineSpecificationProviderRequestTransfer)
            ->setQueryString($merchantCommissionTransfer->getItemConditionOrFail());

        /** @var list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $collectedMerchantCommissionCalculationRequestItems */
        $collectedMerchantCommissionCalculationRequestItems = $this->ruleEngineFacade->collect(
            $merchantCommissionCalculationRequestTransfer,
            $ruleEngineSpecificationRequestTransfer,
        );

        if ($collectedMerchantCommissionCalculationRequestItems === []) {
            return $collectedMerchantCommissionTransfers;
        }

        return $this->merchantCommissionAdder->addCommissionableItemsToCollectedMerchantCommissions(
            $merchantCommissionTransfer,
            $collectedMerchantCommissionCalculationRequestItems,
            $collectedMerchantCommissionTransfers,
        );
    }
}
