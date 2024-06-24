<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\SpecificationProvider;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface;
use SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\DecisionRuleSpecification\TestDecisionRuleAndSpecification;
use SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\DecisionRuleSpecification\TestDecisionRuleContext;
use SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\DecisionRuleSpecification\TestDecisionRuleOrSpecification;

class TestDecisionRuleSpecificationProviderPlugin implements RuleSpecificationProviderPluginInterface
{
    /**
     * @var string
     */
    protected const TEST_DOMAIN_NAME = 'test-domain-name';

    /**
     * @var string
     */
    protected const SPECIFICATION_TYPE = 'decision';

    /**
     * @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface
     */
    protected DecisionRulePluginInterface $rulePlugin;

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface $rulePlugin
     */
    public function __construct(DecisionRulePluginInterface $rulePlugin)
    {
        $this->rulePlugin = $rulePlugin;
    }

    /**
     * @return string
     */
    public function getDomainName(): string
    {
        return static::TEST_DOMAIN_NAME;
    }

    /**
     * @return string
     */
    public function getSpecificationType(): string
    {
        return static::SPECIFICATION_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function getRuleSpecificationContext(RuleEngineClauseTransfer $ruleEngineClauseTransfer): RuleSpecificationInterface
    {
        return new TestDecisionRuleContext($this->rulePlugin, $ruleEngineClauseTransfer);
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function createAnd(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode): RuleSpecificationInterface
    {
        return new TestDecisionRuleAndSpecification($leftNode, $rightNode);
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function createOr(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode): RuleSpecificationInterface
    {
        return new TestDecisionRuleOrSpecification($leftNode, $rightNode);
    }

    /**
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface>
     */
    public function getRulePlugins(): array
    {
        return [
            $this->rulePlugin,
        ];
    }
}
