<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Generated\Shared\Transfer\RuleEngineQueryStringValidationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineQueryStringValidationResponseTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\RuleEngine\Business\RuleEngineBusinessFactory getFactory()
 */
class RuleEngineFacade extends AbstractFacade implements RuleEngineFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return list<\Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function collect(
        TransferInterface $collectableTransfer,
        RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
    ): array {
        return $this->getFactory()
            ->createCollectorRuleExecutor()
            ->collect($collectableTransfer, $ruleEngineSpecificationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        TransferInterface $satisfyingTransfer,
        RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
    ): bool {
        return $this->getFactory()
            ->createDecisionRuleExecutor()
            ->isSatisfiedBy($satisfyingTransfer, $ruleEngineSpecificationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param mixed $comparedValue
     *
     * @return bool
     */
    public function compare(RuleEngineClauseTransfer $ruleEngineClauseTransfer, mixed $comparedValue): bool
    {
        return $this->getFactory()
            ->createComparator()
            ->compare($ruleEngineClauseTransfer, $comparedValue);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RuleEngineQueryStringValidationRequestTransfer $ruleEngineQueryStringValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RuleEngineQueryStringValidationResponseTransfer
     */
    public function validateQueryString(
        RuleEngineQueryStringValidationRequestTransfer $ruleEngineQueryStringValidationRequestTransfer
    ): RuleEngineQueryStringValidationResponseTransfer {
        return $this->getFactory()
            ->createQueryStringValidator()
            ->validate($ruleEngineQueryStringValidationRequestTransfer);
    }
}
