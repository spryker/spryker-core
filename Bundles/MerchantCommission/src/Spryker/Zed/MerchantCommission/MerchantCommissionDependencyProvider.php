<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToCurrencyFacadeBridge;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToMerchantFacadeBridge;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToMoneyFacadeBridge;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeBridge;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantCommission\MerchantCommissionConfig getConfig()
 */
class MerchantCommissionDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';

    /**
     * @var string
     */
    public const FACADE_RULE_ENGINE = 'FACADE_RULE_ENGINE';

    /**
     * @var string
     */
    public const FACADE_MONEY = 'FACADE_MONEY';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_COMMISSION_CALCULATOR = 'PLUGINS_MERCHANT_COMMISSION_CALCULATOR';

    /**
     * @var string
     */
    public const PLUGINS_RULE_ENGINE_COLLECTOR_RULE = 'PLUGINS_RULE_ENGINE_COLLECTOR_RULE';

    /**
     * @var string
     */
    public const PLUGINS_RULE_ENGINE_DECISION_RULE = 'PLUGINS_RULE_ENGINE_DECISION_RULE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addRuleEngineFacade($container);
        $container = $this->addMerchantCommissionCalculatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addRuleEngineCollectorRulePlugins($container);
        $container = $this->addRuleEngineDecisionRulePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new MerchantCommissionToMerchantFacadeBridge(
                $container->getLocator()->merchant()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new MerchantCommissionToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container->set(static::FACADE_CURRENCY, function (Container $container) {
            return new MerchantCommissionToCurrencyFacadeBridge(
                $container->getLocator()->currency()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRuleEngineFacade(Container $container): Container
    {
        $container->set(static::FACADE_RULE_ENGINE, function (Container $container) {
            return new MerchantCommissionToRuleEngineFacadeBridge($container->getLocator()->ruleEngine()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
    {
        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new MerchantCommissionToMoneyFacadeBridge($container->getLocator()->money()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantCommissionCalculatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_COMMISSION_CALCULATOR, function () {
            return $this->getMerchantCommissionCalculatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRuleEngineCollectorRulePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RULE_ENGINE_COLLECTOR_RULE, function () {
            return $this->getRuleEngineCollectorRulePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRuleEngineDecisionRulePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RULE_ENGINE_DECISION_RULE, function () {
            return $this->getRuleEngineDecisionRulePlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface>
     */
    protected function getMerchantCommissionCalculatorPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface>
     */
    protected function getRuleEngineCollectorRulePlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface>
     */
    protected function getRuleEngineDecisionRulePlugins(): array
    {
        return [];
    }
}
