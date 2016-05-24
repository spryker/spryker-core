<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business;

use Spryker\Zed\Discount\Business\Calculator\Calculator;
use Spryker\Zed\Discount\Business\Calculator\Discount;
use Spryker\Zed\Discount\Business\Calculator\Type\Fixed;
use Spryker\Zed\Discount\Business\Calculator\Type\Percentage;
use Spryker\Zed\Discount\Business\Collector\SkuCollector;
use Spryker\Zed\Discount\Business\DecisionRule\GrandTotalDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\ItemSkuDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\ProductAttribute;
use Spryker\Zed\Discount\Business\DecisionRule\SubTotalDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\TotalQuantityDecisionRule;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\Persistence\DiscountConfiguratorHydrate;
use Spryker\Zed\Discount\Business\Persistence\DiscountOrderSaver;
use Spryker\Zed\Discount\Business\Persistence\DiscountPersist;
use Spryker\Zed\Discount\Business\SalesAggregator\DiscountTotalAmount;
use Spryker\Zed\Discount\Business\SalesAggregator\GrandTotalWithDiscounts;
use Spryker\Zed\Discount\Business\SalesAggregator\ItemDiscounts;
use Spryker\Zed\Discount\Business\SalesAggregator\OrderDiscounts;
use Spryker\Zed\Discount\Business\SalesAggregator\OrderExpensesWithDiscounts;
use Spryker\Zed\Discount\Business\SalesAggregator\OrderExpenseTaxWithDiscounts;
use Spryker\Zed\Discount\Business\Voucher\VoucherCode;
use Spryker\Zed\Discount\Business\Voucher\VoucherEngine;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Business\QueryString\OperatorProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaProviderFactory;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\Tokenizer;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider;
use Spryker\Zed\Discount\Business\QueryString\Validator;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidator;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToAssertionInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainer getQueryContainer()
 */
class DiscountBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\Discount
     */
    public function createDiscount()
    {
        return new Discount(
            $this->getQueryContainer(),
            $this->createCalculator(),
            $this->createDecisionRuleBuilder(),
            $this->createVoucherValidator()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Voucher\VoucherValidator
     */
    protected function createVoucherValidator()
    {
        return new VoucherValidator(
            $this->getQueryContainer(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\Type\Percentage
     */
    public function createCalculatorPercentage()
    {
        return new Percentage();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\Type\Fixed
     */
    public function createCalculatorFixed()
    {
        return new Fixed();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\Calculator
     */
    protected function createCalculator()
    {
        return new Calculator(
            $this->createCollectorBuilder(),
            $this->getMessengerFacade(),
            $this->createDistributor(),
            $this->getCalculatorPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Distributor\Distributor
     */
    public function createDistributor()
    {
        return new Distributor();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Voucher\VoucherEngine
     */
    public function createVoucherEngine()
    {
        return new VoucherEngine(
            $this->getConfig(),
            $this->getQueryContainer(),
            $this->getMessengerFacade(),
            $this->getPropelConnection()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Voucher\VoucherCodeInterface
     */
    public function createVoucherCode()
    {
        return new VoucherCode($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[]
     */
    public function getDecisionRulePlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::DECISION_RULE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountOrderSaverInterface
     */
    public function createDiscountOrderSaver()
    {
        return new DiscountOrderSaver($this->getQueryContainer(), $this->createVoucherCode());
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    public function getCalculatorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::CALCULATOR_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface[]
     */
    public function getCollectorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::COLLECTOR_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerBridge
     */
    protected function getMessengerFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getPropelConnection()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_PROPEL_CONNECTION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStoreConfig()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::STORE_CONFIG);
    }

    /**
     * @return DiscountToAssertionInterface
     */
    protected function getAssertionFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_ASSERTION);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\SalesAggregator\DiscountTotalAmount
     */
    public function createOrderDiscountTotalAmount()
    {
        return new DiscountTotalAmount();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\SalesAggregator\ItemDiscounts
     */
    public function createItemTotalOrderAggregator()
    {
        return new ItemDiscounts($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\SalesAggregator\OrderDiscounts
     */
    public function createSalesOrderTotalsAggregator()
    {
        return new OrderDiscounts();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\SalesAggregator\GrandTotalWithDiscounts
     */
    public function createSalesOrderGrandTotalAggregator()
    {
        return new GrandTotalWithDiscounts();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\SalesAggregator\OrderExpenseTaxWithDiscounts
     */
    public function createOrderExpenseTaxWithDiscountsAggregator()
    {
        return new OrderExpenseTaxWithDiscounts(
            $this->getProvidedDependency(DiscountDependencyProvider::FACADE_TAX)
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\SalesAggregator\OrderExpensesWithDiscounts
     */
    public function createOrderExpenseWithDiscountsAggregator()
    {
        return new OrderExpensesWithDiscounts($this->getQueryContainer());
    }

    /**
     * @return ItemSkuDecisionRule
     */
    public function createSkuDecisionRule()
    {
        return new ItemSkuDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return ItemSkuDecisionRule
     */
    public function createProductAttributeDecisionRule()
    {
        return new ProductAttribute($this->createComparatorOperators());
    }

    /**
     * @return SkuCollector
     */
    public function createSkuCollector()
    {
        return new SkuCollector($this->createComparatorOperators());
    }

    /**
     * @return ComparatorOperators
     */
    public function createComparatorOperators()
    {
        return new ComparatorOperators(
            $this->createComparatorProvider()->createComparators()
        );
    }

    /**
     * @return OperatorProvider
     */
    protected function createComparatorProvider()
    {
        return new OperatorProvider();
    }

    /**
     * @return CollectorProvider
     */
    protected function createCollectorProvider()
    {
        return new CollectorProvider($this->getCollectorPlugins());
    }

    /**
     * @return DecisionRuleProvider
     */
    protected function createDecisionRuleProvider()
    {
        return new DecisionRuleProvider($this->getDecisionRulePlugins());
    }

    /**
     * @return SpecificationBuilder
     */
    protected function createDecisionRuleBuilder()
    {
        return new SpecificationBuilder(
             $this->createTokenizer(),
             $this->getAssertionFacade(),
             $this->createDecisionRuleProvider()
        );
    }

    /**
     * @return SpecificationBuilder
     */
    protected function createCollectorBuilder()
    {
        return new SpecificationBuilder(
            $this->createTokenizer(),
            $this->getAssertionFacade(),
            $this->createCollectorProvider()
        );
    }

    /**
     * @return Tokenizer
     */
    protected function createTokenizer()
    {
        return new Tokenizer();
    }

    /**
     * @return MetaProviderFactory
     */
    public function createQueryStringSpecificationMetaProviderFactory()
    {
        return new MetaProviderFactory($this);
    }

    /**
     * @return LogicalComparators
     */
    public function createLogicalComparators()
    {
        return new LogicalComparators();
    }

    /**
     * @return Validator
     */
    public function createQueryStringValidator()
    {
        return new Validator(
            $this->createDecisionRuleBuilder(),
            $this->createCollectorBuilder()
        );
    }

    /*
     * @return DiscountConfiguratorHydrate
     */
    public function createDiscountConfiguratorHydrate()
    {
        return new DiscountConfiguratorHydrate($this->getQueryContainer());
    }

    /**
     * @return DiscountPersist
     */
    public function createDiscountPersist()
    {
        return new DiscountPersist($this->createVoucherEngine(), $this->getQueryContainer());
    }

    /**
     * @return GrandTotalDecisionRule
     */
    public function createGrandTotalDecisionRule()
    {
        return new GrandTotalDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return TotalQuantityDecisionRule
     */
    public function createTotalQuantityDecisionRule()
    {
        return new TotalQuantityDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return SubTotalDecisionRule
     */
    public function createSubTotalDecisionRule()
    {
        return new SubTotalDecisionRule($this->createComparatorOperators());
    }
}

