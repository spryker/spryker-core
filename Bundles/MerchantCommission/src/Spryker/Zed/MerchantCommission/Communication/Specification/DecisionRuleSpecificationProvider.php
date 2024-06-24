<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication\Specification;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\MerchantCommission\Business\Exception\DecisionRulePluginNotFoundException;
use Spryker\Zed\MerchantCommission\Communication\Specification\DecisionRuleSpecification\DecisionRuleAndSpecification;
use Spryker\Zed\MerchantCommission\Communication\Specification\DecisionRuleSpecification\DecisionRuleContext;
use Spryker\Zed\MerchantCommission\Communication\Specification\DecisionRuleSpecification\DecisionRuleOrSpecification;
use Spryker\Zed\MerchantCommission\MerchantCommissionDependencyProvider;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface;

class DecisionRuleSpecificationProvider implements SpecificationProviderInterface
{
    /**
     * @var list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface>
     */
    protected array $ruleEngineDecisionRulePlugins;

    /**
     * @param list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface> $ruleEngineDecisionRulePlugins
     */
    public function __construct(array $ruleEngineDecisionRulePlugins)
    {
        $this->ruleEngineDecisionRulePlugins = $ruleEngineDecisionRulePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @throws \Spryker\Zed\MerchantCommission\Business\Exception\DecisionRulePluginNotFoundException
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface
     */
    public function getSpecificationContext(RuleEngineClauseTransfer $ruleEngineClauseTransfer): RuleSpecificationInterface
    {
        foreach ($this->ruleEngineDecisionRulePlugins as $ruleEngineDecisionRulePlugin) {
            if (strcasecmp($ruleEngineDecisionRulePlugin->getFieldName(), $ruleEngineClauseTransfer->getFieldOrFail()) === 0) {
                return new DecisionRuleContext($ruleEngineDecisionRulePlugin, $ruleEngineClauseTransfer);
            }
        }

        throw new DecisionRulePluginNotFoundException(sprintf(
            'Decision rule plugin for "%s" field not found. You can fix this error by adding it to %s::getDecisionRulePlugins()',
            $ruleEngineClauseTransfer->getFieldOrFail(),
            MerchantCommissionDependencyProvider::class,
        ));
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface
     */
    public function createAnd(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode): RuleSpecificationInterface
    {
        return new DecisionRuleOrSpecification($leftNode, $rightNode);
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface
     */
    public function createOr(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode): RuleSpecificationInterface
    {
        return new DecisionRuleAndSpecification($leftNode, $rightNode);
    }

    /**
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface>
     */
    public function getRulePlugins(): array
    {
        return $this->ruleEngineDecisionRulePlugins;
    }
}
