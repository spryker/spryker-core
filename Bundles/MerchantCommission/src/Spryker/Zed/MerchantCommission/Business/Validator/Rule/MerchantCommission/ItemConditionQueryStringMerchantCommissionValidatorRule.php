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

class ItemConditionQueryStringMerchantCommissionValidatorRule extends AbstractQueryStringMerchantCommissionValidatorRule
{
    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::COLLECTOR_RULE_SPECIFICATION_TYPE
     *
     * @var string
     */
    protected const COLLECTOR_RULE_SPECIFICATION_TYPE = 'collector';

    /**
     * @var string
     */
    protected const FIELD_ITEM_CONDITION = 'item condition';

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
        $itemConditionQueryStringsIndexedByEntityIdentifier = $this->getItemConditionQueryStringsIndexedByEntityIdentifier(
            $merchantCommissionTransfers,
        );

        $ruleEngineSpecificationProviderRequestTransfer = (new RuleEngineSpecificationProviderRequestTransfer())
            ->setDomainName($this->merchantCommissionConfig->getRuleEngineMerchantCommissionDomainName())
            ->setSpecificationRuleType(static::COLLECTOR_RULE_SPECIFICATION_TYPE);
        $ruleEngineQueryStringValidationRequestTransfer = (new RuleEngineQueryStringValidationRequestTransfer())
            ->setRuleEngineSpecificationProviderRequest($ruleEngineSpecificationProviderRequestTransfer)
            ->setQueryStrings($itemConditionQueryStringsIndexedByEntityIdentifier);

        $ruleEngineQueryStringValidationResponseTransfer = $this->ruleEngineFacade->validateQueryString(
            $ruleEngineQueryStringValidationRequestTransfer,
        );

        return (new ErrorCollectionTransfer())->setErrors(
            $this->translateRuleEngineValidationErrors($ruleEngineQueryStringValidationResponseTransfer->getErrors()),
        );
    }

    /**
     * @return string
     */
    protected function getFieldName(): string
    {
        return static::FIELD_ITEM_CONDITION;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers $merchantCommissionTransfers
     *
     * @return array<string|int, string>
     */
    protected function getItemConditionQueryStringsIndexedByEntityIdentifier(ArrayObject $merchantCommissionTransfers): array
    {
        $indexedItemConditionQueryStrings = [];
        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            $itemConditionQueryString = $merchantCommissionTransfer->getItemCondition();
            if (!$itemConditionQueryString) {
                continue;
            }

            $indexedItemConditionQueryStrings[$entityIdentifier] = $itemConditionQueryString;
        }

        return $indexedItemConditionQueryStrings;
    }
}
