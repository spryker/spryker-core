<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Dependency\Facade;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Generated\Shared\Transfer\RuleEngineQueryStringValidationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineQueryStringValidationResponseTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class MerchantCommissionToRuleEngineFacadeBridge implements MerchantCommissionToRuleEngineFacadeInterface
{
    /**
     * @var \Spryker\Zed\RuleEngine\Business\RuleEngineFacadeInterface
     */
    protected $ruleEngineFacade;

    /**
     * @param \Spryker\Zed\RuleEngine\Business\RuleEngineFacadeInterface $ruleEngineFacade
     */
    public function __construct($ruleEngineFacade)
    {
        $this->ruleEngineFacade = $ruleEngineFacade;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return list<\Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function collect(
        TransferInterface $collectableTransfer,
        RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
    ): array {
        return $this->ruleEngineFacade->collect($collectableTransfer, $ruleEngineSpecificationRequestTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        TransferInterface $satisfyingTransfer,
        RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
    ): bool {
        return $this->ruleEngineFacade->isSatisfiedBy($satisfyingTransfer, $ruleEngineSpecificationRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param mixed $comparedValue
     *
     * @return bool
     */
    public function compare(RuleEngineClauseTransfer $ruleEngineClauseTransfer, mixed $comparedValue): bool
    {
        return $this->ruleEngineFacade->compare($ruleEngineClauseTransfer, $comparedValue);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineQueryStringValidationRequestTransfer $ruleEngineQueryStringValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RuleEngineQueryStringValidationResponseTransfer
     */
    public function validateQueryString(
        RuleEngineQueryStringValidationRequestTransfer $ruleEngineQueryStringValidationRequestTransfer
    ): RuleEngineQueryStringValidationResponseTransfer {
        return $this->ruleEngineFacade->validateQueryString($ruleEngineQueryStringValidationRequestTransfer);
    }
}
