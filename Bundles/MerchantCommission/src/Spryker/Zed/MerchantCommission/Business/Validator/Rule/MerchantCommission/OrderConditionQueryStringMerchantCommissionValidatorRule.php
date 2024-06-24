<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\RuleEngineQueryStringValidationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationProviderRequestTransfer;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface;
use Spryker\Zed\MerchantCommission\MerchantCommissionConfig;

class OrderConditionQueryStringMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface
{
    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::DECISION_RULE_SPECIFICATION_TYPE
     *
     * @var string
     */
    protected const DECISION_RULE_SPECIFICATION_TYPE = 'decision';

    /**
     * @var \Spryker\Zed\MerchantCommission\MerchantCommissionConfig
     */
    protected MerchantCommissionConfig $merchantCommissionConfig;

    /**
     * @var \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface
     */
    protected MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade;

    /**
     * @param \Spryker\Zed\MerchantCommission\MerchantCommissionConfig $merchantCommissionConfig
     * @param \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade
     */
    public function __construct(
        MerchantCommissionConfig $merchantCommissionConfig,
        MerchantCommissionToRuleEngineFacadeInterface $ruleEngineFacade
    ) {
        $this->merchantCommissionConfig = $merchantCommissionConfig;
        $this->ruleEngineFacade = $ruleEngineFacade;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantCommissionTransfers): ErrorCollectionTransfer
    {
        $orderConditionQueryStringsIndexedByEntityIdentifier = $this->getOrderConditionQueryStringsIndexedByEntityIdentifier(
            $merchantCommissionTransfers,
        );

        $ruleEngineSpecificationProviderRequestTransfer = (new RuleEngineSpecificationProviderRequestTransfer())
            ->setDomainName($this->merchantCommissionConfig->getRuleEngineMerchantCommissionDomainName())
            ->setSpecificationRuleType(static::DECISION_RULE_SPECIFICATION_TYPE);
        $ruleEngineQueryStringValidationRequestTransfer = (new RuleEngineQueryStringValidationRequestTransfer())
            ->setRuleEngineSpecificationProviderRequest($ruleEngineSpecificationProviderRequestTransfer)
            ->setQueryStrings($orderConditionQueryStringsIndexedByEntityIdentifier);

        $ruleEngineQueryStringValidationResponseTransfer = $this->ruleEngineFacade->validateQueryString(
            $ruleEngineQueryStringValidationRequestTransfer,
        );

        return (new ErrorCollectionTransfer())->setErrors($ruleEngineQueryStringValidationResponseTransfer->getErrors());
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers $merchantCommissionTransfers
     *
     * @return array<string|int, string>
     */
    protected function getOrderConditionQueryStringsIndexedByEntityIdentifier(ArrayObject $merchantCommissionTransfers): array
    {
        $indexedOrderConditionQueryStrings = [];
        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            $orderConditionQueryString = $merchantCommissionTransfer->getOrderCondition();
            if (!$orderConditionQueryString) {
                continue;
            }

            $indexedOrderConditionQueryStrings[$entityIdentifier] = $orderConditionQueryString;
        }

        return $indexedOrderConditionQueryStrings;
    }
}
