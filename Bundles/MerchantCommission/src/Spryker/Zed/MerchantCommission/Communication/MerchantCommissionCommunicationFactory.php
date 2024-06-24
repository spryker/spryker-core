<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantCommission\Communication\Specification\CollectorRuleSpecificationProvider;
use Spryker\Zed\MerchantCommission\Communication\Specification\DecisionRuleSpecificationProvider;
use Spryker\Zed\MerchantCommission\Communication\Specification\SpecificationProviderInterface;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToMoneyFacadeInterface;
use Spryker\Zed\MerchantCommission\MerchantCommissionDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantCommission\MerchantCommissionConfig getConfig()
 * @method \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacadeInterface getFacade()
 */
class MerchantCommissionCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToMoneyFacadeInterface
     */
    public function getMoneyFacade(): MerchantCommissionToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantCommissionDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\MerchantCommission\Communication\Specification\SpecificationProviderInterface
     */
    public function createCollectorSpecificationProvider(): SpecificationProviderInterface
    {
        return new CollectorRuleSpecificationProvider($this->getRuleEngineCollectorPlugins());
    }

    /**
     * @return \Spryker\Zed\MerchantCommission\Communication\Specification\SpecificationProviderInterface
     */
    public function createDecisionRuleSpecificationProvider(): SpecificationProviderInterface
    {
        return new DecisionRuleSpecificationProvider($this->getRuleEngineDecisionRulePlugins());
    }

    /**
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface>
     */
    public function getRuleEngineCollectorPlugins(): array
    {
        return $this->getProvidedDependency(MerchantCommissionDependencyProvider::PLUGINS_RULE_ENGINE_COLLECTOR_RULE);
    }

    /**
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface>
     */
    public function getRuleEngineDecisionRulePlugins(): array
    {
        return $this->getProvidedDependency(MerchantCommissionDependencyProvider::PLUGINS_RULE_ENGINE_DECISION_RULE);
    }
}
