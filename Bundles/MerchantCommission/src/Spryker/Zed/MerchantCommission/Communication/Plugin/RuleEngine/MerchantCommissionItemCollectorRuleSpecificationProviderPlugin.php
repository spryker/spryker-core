<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface;

/**
 * @method \Spryker\Zed\MerchantCommission\MerchantCommissionConfig getConfig()
 * @method \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantCommission\Communication\MerchantCommissionCommunicationFactory getFactory()
 */
class MerchantCommissionItemCollectorRuleSpecificationProviderPlugin extends AbstractPlugin implements RuleSpecificationProviderPluginInterface
{
    /**
     * @uses \Spryker\Zed\RuleEngine\RuleEngineConfig::DECISION_RULE_SPECIFICATION_TYPE
     *
     * @var string
     */
    protected const COLLECTOR_RULE_SPECIFICATION_TYPE = 'collector';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getDomainName(): string
    {
        return $this->getConfig()->getRuleEngineMerchantCommissionDomainName();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getSpecificationType(): string
    {
        return static::COLLECTOR_RULE_SPECIFICATION_TYPE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function getRuleSpecificationContext(RuleEngineClauseTransfer $ruleEngineClauseTransfer): RuleSpecificationInterface
    {
        return $this->getFactory()
            ->createCollectorSpecificationProvider()
            ->getSpecificationContext($ruleEngineClauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function createAnd(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode): RuleSpecificationInterface
    {
        return $this->getFactory()
            ->createCollectorSpecificationProvider()
            ->createAnd($leftNode, $rightNode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function createOr(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode): RuleSpecificationInterface
    {
        return $this->getFactory()
            ->createCollectorSpecificationProvider()
            ->createOr($leftNode, $rightNode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface>
     */
    public function getRulePlugins(): array
    {
        return $this->getFactory()
            ->createCollectorSpecificationProvider()
            ->getRulePlugins();
    }
}
