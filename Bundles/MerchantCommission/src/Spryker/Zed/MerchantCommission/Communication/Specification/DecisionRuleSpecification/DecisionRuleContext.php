<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication\Specification\DecisionRuleSpecification;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface;

class DecisionRuleContext implements DecisionRuleSpecificationInterface
{
    /**
     * @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface
     */
    protected DecisionRulePluginInterface $decisionRulePlugin;

    /**
     * @var \Generated\Shared\Transfer\RuleEngineClauseTransfer
     */
    protected RuleEngineClauseTransfer $ruleEngineClauseTransfer;

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface $decisionRulePlugin
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     */
    public function __construct(DecisionRulePluginInterface $decisionRulePlugin, RuleEngineClauseTransfer $ruleEngineClauseTransfer)
    {
        $this->decisionRulePlugin = $decisionRulePlugin;
        $this->ruleEngineClauseTransfer = $ruleEngineClauseTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(TransferInterface $satisfyingTransfer): bool
    {
        $this->ruleEngineClauseTransfer->setAcceptedTypes($this->decisionRulePlugin->acceptedDataTypes());

        return $this->decisionRulePlugin->isSatisfiedBy($satisfyingTransfer, $this->ruleEngineClauseTransfer);
    }
}
