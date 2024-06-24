<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Filter;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface;
use Spryker\Zed\MerchantCommission\MerchantCommissionConfig;

class MerchantCommissionFilter implements MerchantCommissionFilterInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\MerchantCommissionConfig
     */
    protected MerchantCommissionConfig $merchantCommissionConfig;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface
     */
    protected MerchantDataExtractorInterface $merchantDataExtractor;

    /**
     * @var \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface
     */
    protected MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade;

    /**
     * @param \Spryker\Zed\MerchantCommission\MerchantCommissionConfig $merchantCommissionConfig
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface $merchantDataExtractor
     * @param \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade
     */
    public function __construct(
        MerchantCommissionConfig $merchantCommissionConfig,
        MerchantDataExtractorInterface $merchantDataExtractor,
        MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade
    ) {
        $this->merchantCommissionConfig = $merchantCommissionConfig;
        $this->merchantDataExtractor = $merchantDataExtractor;
        $this->ruleEngineFacade = $ruleEngineFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param array<string, list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>> $merchantCommissionCalculationRequestItemsGroupedByMerchantReference
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function filterOutNotApplicableMerchantCommissions(
        MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        array $merchantCommissionCalculationRequestItemsGroupedByMerchantReference
    ): array {
        $applicableMerchantCommissionTransfers = [];
        foreach ($merchantCommissionCollectionTransfer->getMerchantCommissions() as $merchantCommissionTransfer) {
            if (
                !$this->isMerchantCommissionApplicable(
                    $merchantCommissionTransfer,
                    $merchantCommissionCalculationRequestTransfer,
                    $merchantCommissionCalculationRequestItemsGroupedByMerchantReference,
                )
            ) {
                continue;
            }

            $applicableMerchantCommissionTransfers[] = $merchantCommissionTransfer;
        }

        return $applicableMerchantCommissionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param array<string, list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>> $merchantCommissionCalculationRequestItemsGroupedByMerchantReference
     *
     * @return bool
     */
    protected function isMerchantCommissionApplicable(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        array $merchantCommissionCalculationRequestItemsGroupedByMerchantReference
    ): bool {
        if (!$this->hasAllowedMerchants($merchantCommissionTransfer, $merchantCommissionCalculationRequestItemsGroupedByMerchantReference)) {
            return false;
        }

        return $this->isOrderConditionSatisfied(
            $merchantCommissionTransfer,
            $merchantCommissionCalculationRequestTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param array<string, list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>> $merchantCommissionCalculationRequestItemsIndexedByMerchantReference
     *
     * @return bool
     */
    protected function hasAllowedMerchants(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        array $merchantCommissionCalculationRequestItemsIndexedByMerchantReference
    ): bool {
        if ($merchantCommissionTransfer->getMerchants()->count() === 0) {
            return true;
        }

        $merchantReferences = $this->merchantDataExtractor->extractMerchantReferencesFromMerchantTransfers(
            $merchantCommissionTransfer->getMerchants(),
        );

        return array_intersect_key($merchantCommissionCalculationRequestItemsIndexedByMerchantReference, array_flip($merchantReferences)) !== [];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return bool
     */
    protected function isOrderConditionSatisfied(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): bool {
        $orderCondition = $merchantCommissionTransfer->getOrderCondition();
        if (!$orderCondition) {
            return true;
        }

        $ruleEngineSpecificationProviderRequestTransfer = (new RuleEngineSpecificationProviderRequestTransfer())
            ->setDomainName($this->merchantCommissionConfig->getRuleEngineMerchantCommissionDomainName());
        $ruleEngineSpecificationRequestTransfer = (new RuleEngineSpecificationRequestTransfer())
            ->setRuleEngineSpecificationProviderRequest($ruleEngineSpecificationProviderRequestTransfer)
            ->setQueryString($orderCondition);

        return $this->ruleEngineFacade->isSatisfiedBy(
            $merchantCommissionCalculationRequestTransfer,
            $ruleEngineSpecificationRequestTransfer,
        );
    }
}
