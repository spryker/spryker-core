<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication\Specification;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\MerchantCommission\Business\Exception\CollectorPluginNotFoundException;
use Spryker\Zed\MerchantCommission\Communication\Specification\CollectorRuleSpecification\CollectorRuleAndSpecification;
use Spryker\Zed\MerchantCommission\Communication\Specification\CollectorRuleSpecification\CollectorRuleContext;
use Spryker\Zed\MerchantCommission\Communication\Specification\CollectorRuleSpecification\CollectorRuleOrSpecification;
use Spryker\Zed\MerchantCommission\MerchantCommissionDependencyProvider;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface;

class CollectorRuleSpecificationProvider implements SpecificationProviderInterface
{
    /**
     * @var list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface>
     */
    protected array $ruleEngineCollectorRulePlugins;

    /**
     * @param list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface> $ruleEngineCollectorRulePlugins
     */
    public function __construct(array $ruleEngineCollectorRulePlugins)
    {
        $this->ruleEngineCollectorRulePlugins = $ruleEngineCollectorRulePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @throws \Spryker\Zed\MerchantCommission\Business\Exception\CollectorPluginNotFoundException
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface
     */
    public function getSpecificationContext(RuleEngineClauseTransfer $ruleEngineClauseTransfer): CollectorRuleSpecificationInterface
    {
        foreach ($this->ruleEngineCollectorRulePlugins as $ruleEngineCollectorRulePlugin) {
            if (strcasecmp($ruleEngineCollectorRulePlugin->getFieldName(), $ruleEngineClauseTransfer->getFieldOrFail()) === 0) {
                return new CollectorRuleContext($ruleEngineCollectorRulePlugin, $ruleEngineClauseTransfer);
            }
        }

        throw new CollectorPluginNotFoundException(sprintf(
            'Collector plugin for "%s" field not found. You can fix this error by adding it to %s::getCollectorPlugins()',
            $ruleEngineClauseTransfer->getFieldOrFail(),
            MerchantCommissionDependencyProvider::class,
        ));
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface
     */
    public function createAnd(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode): CollectorRuleSpecificationInterface
    {
        return new CollectorRuleAndSpecification($leftNode, $rightNode);
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface
     */
    public function createOr(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode): RuleSpecificationInterface
    {
        return new CollectorRuleOrSpecification($leftNode, $rightNode);
    }

    /**
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface>
     */
    public function getRulePlugins(): array
    {
        return $this->ruleEngineCollectorRulePlugins;
    }
}
