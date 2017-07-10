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
use Spryker\Zed\Discount\Business\Collector\ItemPriceCollector;
use Spryker\Zed\Discount\Business\Collector\ItemQuantityCollector;
use Spryker\Zed\Discount\Business\Collector\SkuCollector;
use Spryker\Zed\Discount\Business\DecisionRule\CalendarWeekDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\DayOfWeekDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\GrandTotalDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\ItemPriceDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\ItemQuantityDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\ItemSkuDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\MonthDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\SubTotalDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\TimeDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\TotalQuantityDecisionRule;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\Filter\DiscountableItemFilter;
use Spryker\Zed\Discount\Business\Persistence\DiscountConfiguratorHydrate;
use Spryker\Zed\Discount\Business\Persistence\DiscountOrderHydrate;
use Spryker\Zed\Discount\Business\Persistence\DiscountOrderSaver;
use Spryker\Zed\Discount\Business\Persistence\DiscountPersist;
use Spryker\Zed\Discount\Business\QueryString\ClauseValidator;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverter;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Business\QueryString\OperatorProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactory;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\Tokenizer;
use Spryker\Zed\Discount\Business\QueryString\Validator;
use Spryker\Zed\Discount\Business\Voucher\VoucherCode;
use Spryker\Zed\Discount\Business\Voucher\VoucherEngine;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidator;
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
            $this->getQueryContainer()
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
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]|\Spryker\Zed\Discount\Dependency\Plugin\DiscountAmountCalculatorPluginInterface[]
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
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStoreConfig()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::STORE_CONFIG);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\ItemSkuDecisionRule
     */
    public function createSkuDecisionRule()
    {
        return new ItemSkuDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\SkuCollector
     */
    public function createSkuCollector()
    {
        return new SkuCollector($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    public function createComparatorOperators()
    {
        return new ComparatorOperators(
            $this->createComparatorProvider()->createComparators()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\OperatorProvider
     */
    protected function createComparatorProvider()
    {
        return new OperatorProvider();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider
     */
    protected function createCollectorProvider()
    {
        return new CollectorProvider($this->getCollectorPlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleProvider
     */
    protected function createDecisionRuleProvider()
    {
        return new DecisionRuleProvider($this->getDecisionRulePlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder
     */
    protected function createDecisionRuleBuilder()
    {
        return new SpecificationBuilder(
            $this->createTokenizer(),
            $this->createDecisionRuleProvider(),
            $this->createComparatorOperators(),
            $this->createClauseValidator(MetaProviderFactory::TYPE_DECISION_RULE),
            $this->createMetaDataProviderByType(MetaProviderFactory::TYPE_DECISION_RULE)
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder
     */
    protected function createCollectorBuilder()
    {
        return new SpecificationBuilder(
            $this->createTokenizer(),
            $this->createCollectorProvider(),
            $this->createComparatorOperators(),
            $this->createClauseValidator(MetaProviderFactory::TYPE_COLLECTOR),
            $this->createMetaDataProviderByType(MetaProviderFactory::TYPE_COLLECTOR)
        );
    }

    /**
     * @param string $type
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\ClauseValidator
     */
    protected function createClauseValidator($type)
    {
        return new ClauseValidator(
            $this->createComparatorOperators(),
            $this->createMetaDataProviderByType($type)
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Tokenizer
     */
    protected function createTokenizer()
    {
        return new Tokenizer();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactory
     */
    public function createQueryStringMetaDataProviderFactory()
    {
        return new MetaProviderFactory($this);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\LogicalComparators
     */
    public function createLogicalComparators()
    {
        return new LogicalComparators();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Validator
     */
    public function createQueryStringValidator()
    {
        return new Validator(
            $this->createDecisionRuleBuilder(),
            $this->createCollectorBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountConfiguratorHydrate
     */
    public function createDiscountConfiguratorHydrate()
    {
        return new DiscountConfiguratorHydrate($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountPersist
     */
    public function createDiscountPersist()
    {
        return new DiscountPersist($this->createVoucherEngine(), $this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\GrandTotalDecisionRule
     */
    public function createGrandTotalDecisionRule()
    {
        return new GrandTotalDecisionRule($this->createComparatorOperators(), $this->createMoneyValueConverter());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\TotalQuantityDecisionRule
     */
    public function createTotalQuantityDecisionRule()
    {
        return new TotalQuantityDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\SubTotalDecisionRule
     */
    public function createSubTotalDecisionRule()
    {
        return new SubTotalDecisionRule($this->createComparatorOperators(), $this->createMoneyValueConverter());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\ItemQuantityCollector
     */
    public function createItemQuantityCollector()
    {
        return new ItemQuantityCollector($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\ItemQuantityDecisionRule
     */
    public function createItemQuantityDecisionRule()
    {
        return new ItemQuantityDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\ItemPriceDecisionRule
     */
    public function createItemPriceDecisionRule()
    {
        return new ItemPriceDecisionRule($this->createComparatorOperators(), $this->createMoneyValueConverter());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\ItemPriceCollector
     */
    public function createItemPriceCollector()
    {
        return new ItemPriceCollector($this->createComparatorOperators(), $this->createMoneyValueConverter());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\DayOfWeekDecisionRule
     */
    public function createDayOfWeekDecisionRule()
    {
        return new DayOfWeekDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\CalendarWeekDecisionRule
     */
    public function createCalendarWeekDecisionRule()
    {
        return new CalendarWeekDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\MonthDecisionRule
     */
    public function createMonthDecisionRule()
    {
        return new MonthDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\TimeDecisionRule
     */
    public function createTimeDecisionRule()
    {
        return new TimeDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountOrderHydrateInterface
     */
    public function createDiscountOrderHydrate()
    {
        return new DiscountOrderHydrate($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface
     */
    public function getMoneyFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_MONEY);
    }

    /**
     * @param string $type
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider
     */
    protected function createMetaDataProviderByType($type)
    {
        return $this->createQueryStringMetaDataProviderFactory()
            ->createMetaProviderByType($type);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverter
     */
    protected function createMoneyValueConverter()
    {
        return new MoneyValueConverter($this->getMoneyFacade());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Filter\DiscountableItemFilterInterface
     */
    protected function createDiscountableItemFilter()
    {
        return new DiscountableItemFilter($this->getDiscountableItemFilterPlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface[]
     */
    protected function getDiscountableItemFilterPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNTABLE_ITEM_FILTER);
    }

}
