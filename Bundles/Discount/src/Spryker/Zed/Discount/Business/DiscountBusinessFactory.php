<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business;

use Spryker\Zed\Discount\Business\Calculator\CollectorStrategyResolver;
use Spryker\Zed\Discount\Business\Calculator\Discount;
use Spryker\Zed\Discount\Business\Calculator\FilteredCalculator;
use Spryker\Zed\Discount\Business\Calculator\Type\FixedType;
use Spryker\Zed\Discount\Business\Calculator\Type\PercentageType;
use Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeAdder;
use Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeAdderInterface;
use Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeClearer;
use Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeClearerInterface;
use Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeOperationMessageFinder;
use Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeOperationMessageFinderInterface;
use Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeRemover;
use Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeRemoverInterface;
use Spryker\Zed\Discount\Business\Checkout\DiscountOrderSaver;
use Spryker\Zed\Discount\Business\Collector\ItemPriceCollector;
use Spryker\Zed\Discount\Business\Collector\ItemQuantityCollector;
use Spryker\Zed\Discount\Business\Collector\SkuCollector;
use Spryker\Zed\Discount\Business\Creator\DiscountAmountCreator;
use Spryker\Zed\Discount\Business\Creator\DiscountAmountCreatorInterface;
use Spryker\Zed\Discount\Business\Creator\DiscountCreateAggregator;
use Spryker\Zed\Discount\Business\Creator\DiscountCreateAggregatorInterface;
use Spryker\Zed\Discount\Business\Creator\DiscountCreator;
use Spryker\Zed\Discount\Business\Creator\DiscountCreatorInterface;
use Spryker\Zed\Discount\Business\Creator\DiscountStoreCreator;
use Spryker\Zed\Discount\Business\Creator\DiscountStoreCreatorInterface;
use Spryker\Zed\Discount\Business\Creator\DiscountVoucherPoolCreator;
use Spryker\Zed\Discount\Business\Creator\DiscountVoucherPoolCreatorInterface;
use Spryker\Zed\Discount\Business\DecisionRule\CalendarWeekDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\CurrencyDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\DayOfWeekDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\GrandTotalDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\ItemPriceDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\ItemQuantityDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\ItemSkuDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\MonthDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\PriceModeDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\SubTotalDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\TimeDecisionRule;
use Spryker\Zed\Discount\Business\DecisionRule\TotalQuantityDecisionRule;
use Spryker\Zed\Discount\Business\Deleter\DiscountVoucherPoolDeleter;
use Spryker\Zed\Discount\Business\Deleter\DiscountVoucherPoolDeleterInterface;
use Spryker\Zed\Discount\Business\Deleter\SalesDiscountDeleter;
use Spryker\Zed\Discount\Business\Deleter\SalesDiscountDeleterInterface;
use Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformer;
use Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformerInterface;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\Distributor\DistributorInterface;
use Spryker\Zed\Discount\Business\Filter\CollectedDiscountItemFilter;
use Spryker\Zed\Discount\Business\Filter\CollectedDiscountItemFilterInterface;
use Spryker\Zed\Discount\Business\Filter\DiscountableItemFilter;
use Spryker\Zed\Discount\Business\Mapper\DiscountMapper;
use Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface;
use Spryker\Zed\Discount\Business\Persistence\DiscountConfiguratorHydrate;
use Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapper;
use Spryker\Zed\Discount\Business\Persistence\DiscountOrderHydrate;
use Spryker\Zed\Discount\Business\Persistence\DiscountPersist;
use Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationMapper;
use Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationReader;
use Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationWriter;
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
use Spryker\Zed\Discount\Business\QuoteChangeObserver\QuoteChangeObserver;
use Spryker\Zed\Discount\Business\QuoteChangeObserver\QuoteChangeObserverInterface;
use Spryker\Zed\Discount\Business\QuoteDiscountValidator\QuoteDiscountMaxUsageValidator;
use Spryker\Zed\Discount\Business\Sorter\CollectedDiscountSorter;
use Spryker\Zed\Discount\Business\Sorter\CollectedDiscountSorterInterface;
use Spryker\Zed\Discount\Business\Updater\DiscountAmountUpdater;
use Spryker\Zed\Discount\Business\Updater\DiscountAmountUpdaterInterface;
use Spryker\Zed\Discount\Business\Updater\DiscountStoreUpdater;
use Spryker\Zed\Discount\Business\Updater\DiscountStoreUpdaterInterface;
use Spryker\Zed\Discount\Business\Updater\DiscountUpdateAggregator;
use Spryker\Zed\Discount\Business\Updater\DiscountUpdateAggregatorInterface;
use Spryker\Zed\Discount\Business\Updater\DiscountUpdater;
use Spryker\Zed\Discount\Business\Updater\DiscountUpdaterInterface;
use Spryker\Zed\Discount\Business\Updater\DiscountVoucherPoolUpdater;
use Spryker\Zed\Discount\Business\Updater\DiscountVoucherPoolUpdaterInterface;
use Spryker\Zed\Discount\Business\Updater\SalesOrderDiscountCodeUpdater;
use Spryker\Zed\Discount\Business\Updater\SalesOrderDiscountCodeUpdaterInterface;
use Spryker\Zed\Discount\Business\Updater\SalesOrderDiscountUpdater;
use Spryker\Zed\Discount\Business\Updater\SalesOrderDiscountUpdaterInterface;
use Spryker\Zed\Discount\Business\Validator\ConstraintProvider\DiscountConfiguratorConstraintProviderInterface;
use Spryker\Zed\Discount\Business\Validator\ConstraintProvider\DiscountConfiguratorPeriodConstraintProvider;
use Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorDiscountExistsValidator;
use Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorPeriodValidator;
use Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorComposite;
use Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface;
use Spryker\Zed\Discount\Business\Voucher\VoucherCode;
use Spryker\Zed\Discount\Business\Voucher\VoucherEngine;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidator;
use Spryker\Zed\Discount\Dependency\External\DiscountToValidationAdapterInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToTranslatorFacadeInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 * @method \Spryker\Zed\Discount\Persistence\DiscountEntityManagerInterface getEntityManager()
 */
class DiscountBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\DiscountInterface
     */
    public function createDiscount()
    {
        $discount = new Discount(
            $this->getQueryContainer(),
            $this->createCalculator(),
            $this->createDecisionRuleBuilder(),
            $this->createVoucherValidator(),
            $this->createDiscountEntityMapper(),
            $this->getStoreFacade(),
        );

        $discount->setDiscountApplicableFilterPlugins($this->getDiscountApplicableFilterPlugins());

        return $discount;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Voucher\VoucherValidatorInterface
     */
    protected function createVoucherValidator()
    {
        return new VoucherValidator(
            $this->getQueryContainer(),
            $this->getMessengerFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeAdderInterface
     */
    public function createVoucherCartCodeAdder(): VoucherCartCodeAdderInterface
    {
        return new VoucherCartCodeAdder();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeRemoverInterface
     */
    public function createVoucherCartCodeRemover(): VoucherCartCodeRemoverInterface
    {
        return new VoucherCartCodeRemover();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeClearerInterface
     */
    public function createVoucherCartCodeClearer(): VoucherCartCodeClearerInterface
    {
        return new VoucherCartCodeClearer();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\CartCode\VoucherCartCodeOperationMessageFinderInterface
     */
    public function createVoucherCartCodeOperationMessageFinder(): VoucherCartCodeOperationMessageFinderInterface
    {
        return new VoucherCartCodeOperationMessageFinder(
            $this->getDiscountVoucherApplyCheckerStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\Type\CalculatorTypeInterface
     */
    public function createCalculatorPercentageType()
    {
        return new PercentageType();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\Type\CalculatorTypeInterface
     */
    public function createCalculatorFixedType()
    {
        return new FixedType();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Distributor\DistributorInterface
     */
    public function createDistributor(): DistributorInterface
    {
        return new Distributor(
            $this->createDiscountableItemTransformer(),
            $this->getDiscountableItemTransformerStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Voucher\VoucherEngineInterface
     */
    public function createVoucherEngine()
    {
        return new VoucherEngine(
            $this->getConfig(),
            $this->getQueryContainer(),
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
     * @return array<\Spryker\Zed\DiscountExtension\Dependency\Plugin\DecisionRulePluginInterface>
     */
    public function getDecisionRulePlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::DECISION_RULE_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemTransformerStrategyPluginInterface>
     */
    public function getDiscountableItemTransformerStrategyPlugins(): array
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNTABLE_ITEM_TRANSFORMER_STRATEGY);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Checkout\DiscountOrderSaverInterface
     */
    public function createCheckoutDiscountOrderSaver()
    {
        return new DiscountOrderSaver(
            $this->getQueryContainer(),
            $this->createVoucherCode(),
        );
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface>
     */
    public function getCalculatorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::CALCULATOR_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemCollectorPluginInterface>
     */
    public function getCollectorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::COLLECTOR_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\DiscountExtension\Dependency\Plugin\CollectedDiscountGroupingStrategyPluginInterface>
     */
    public function getCollectedDiscountGroupingPlugins(): array
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::COLLECTED_DISCOUNT_GROUPING_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface
     */
    protected function getMessengerFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\ItemSkuDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createSkuDecisionRule()
    {
        return new ItemSkuDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\SkuCollector|\Spryker\Zed\Discount\Business\Collector\CollectorInterface
     */
    public function createSkuCollector()
    {
        return new SkuCollector($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    public function createComparatorOperators()
    {
        return new ComparatorOperators(
            $this->createComparatorProvider()->createComparators(),
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
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider|\Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface
     */
    protected function createCollectorProvider()
    {
        return new CollectorProvider($this->getCollectorPlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleProvider|\Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface
     */
    protected function createDecisionRuleProvider()
    {
        return new DecisionRuleProvider($this->getDecisionRulePlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface
     */
    protected function createDecisionRuleBuilder()
    {
        return new SpecificationBuilder(
            $this->createTokenizer(),
            $this->createDecisionRuleProvider(),
            $this->createComparatorOperators(),
            $this->createClauseValidator(MetaProviderFactory::TYPE_DECISION_RULE),
            $this->createMetaDataProviderByType(MetaProviderFactory::TYPE_DECISION_RULE),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface
     */
    protected function createCollectorBuilder()
    {
        return new SpecificationBuilder(
            $this->createTokenizer(),
            $this->createCollectorProvider(),
            $this->createComparatorOperators(),
            $this->createClauseValidator(MetaProviderFactory::TYPE_COLLECTOR),
            $this->createMetaDataProviderByType(MetaProviderFactory::TYPE_COLLECTOR),
        );
    }

    /**
     * @param string $type
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\ClauseValidatorInterface
     */
    protected function createClauseValidator($type)
    {
        return new ClauseValidator(
            $this->createComparatorOperators(),
            $this->createMetaDataProviderByType($type),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\TokenizerInterface
     */
    protected function createTokenizer()
    {
        return new Tokenizer();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactoryInterface
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
     * @return \Spryker\Zed\Discount\Business\QueryString\ValidatorInterface
     */
    public function createQueryStringValidator()
    {
        return new Validator(
            $this->createDecisionRuleBuilder(),
            $this->createCollectorBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountConfiguratorHydrateInterface
     */
    public function createDiscountConfiguratorHydrate()
    {
        $discountConfiguratorHydrate = new DiscountConfiguratorHydrate(
            $this->getQueryContainer(),
            $this->createDiscountEntityMapper(),
            $this->createDiscountStoreRelationMapper(),
            $this->getConfigurationExpanderPlugins(),
        );

        return $discountConfiguratorHydrate;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountPersistInterface
     */
    public function createDiscountPersist()
    {
        $discountPersist = new DiscountPersist(
            $this->createVoucherEngine(),
            $this->getQueryContainer(),
            $this->createDiscountStoreRelationWriter(),
            $this->getDiscountPostCreatePlugins(),
            $this->getDiscountPostUpdatePlugins(),
        );

        return $discountPersist;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\GrandTotalDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createGrandTotalDecisionRule()
    {
        return new GrandTotalDecisionRule($this->createComparatorOperators(), $this->createMoneyValueConverter());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\TotalQuantityDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createTotalQuantityDecisionRule()
    {
        return new TotalQuantityDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\SubTotalDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createSubTotalDecisionRule()
    {
        return new SubTotalDecisionRule($this->createComparatorOperators(), $this->createMoneyValueConverter());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\ItemQuantityCollector|\Spryker\Zed\Discount\Business\Collector\CollectorInterface
     */
    public function createItemQuantityCollector()
    {
        return new ItemQuantityCollector($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\ItemQuantityDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createItemQuantityDecisionRule()
    {
        return new ItemQuantityDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\ItemPriceDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createItemPriceDecisionRule()
    {
        return new ItemPriceDecisionRule($this->createComparatorOperators(), $this->createMoneyValueConverter());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\ItemPriceCollector|\Spryker\Zed\Discount\Business\Collector\CollectorInterface
     */
    public function createItemPriceCollector()
    {
        return new ItemPriceCollector($this->createComparatorOperators(), $this->createMoneyValueConverter());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\DayOfWeekDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createDayOfWeekDecisionRule()
    {
        return new DayOfWeekDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\CalendarWeekDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createCalendarWeekDecisionRule()
    {
        return new CalendarWeekDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\MonthDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createMonthDecisionRule()
    {
        return new MonthDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\TimeDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
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
     * @return \Spryker\Zed\Discount\Business\Updater\SalesOrderDiscountUpdaterInterface
     */
    public function createSalesOrderDiscountUpdater(): SalesOrderDiscountUpdaterInterface
    {
        return new SalesOrderDiscountUpdater(
            $this->createSalesDiscountDeleter(),
            $this->createCheckoutDiscountOrderSaver(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Updater\SalesOrderDiscountCodeUpdaterInterface
     */
    public function createSalesOrderDiscountCodeUpdater(): SalesOrderDiscountCodeUpdaterInterface
    {
        return new SalesOrderDiscountCodeUpdater(
            $this->getRepository(),
            $this->createVoucherCode(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Deleter\SalesDiscountDeleterInterface
     */
    public function createSalesDiscountDeleter(): SalesDiscountDeleterInterface
    {
        return new SalesDiscountDeleter($this->getEntityManager(), $this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Deleter\DiscountVoucherPoolDeleterInterface
     */
    public function createDiscountVoucherPoolDeleter(): DiscountVoucherPoolDeleterInterface
    {
        return new DiscountVoucherPoolDeleter($this->getEntityManager());
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
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProviderInterface
     */
    protected function createMetaDataProviderByType($type)
    {
        return $this->createQueryStringMetaDataProviderFactory()
            ->createMetaProviderByType($type);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface
     */
    protected function createMoneyValueConverter()
    {
        return new MoneyValueConverter($this->getMoneyFacade());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\CalculatorInterface
     */
    protected function createCalculator()
    {
        $calculator = new FilteredCalculator(
            $this->createCollectorBuilder(),
            $this->getMessengerFacade(),
            $this->createDistributor(),
            $this->getCalculatorPlugins(),
            $this->getCollectedDiscountGroupingPlugins(),
            $this->createCollectedDiscountItemFilter(),
            $this->createCollectedDiscountSorter(),
            $this->createDiscountableItemFilter(),
        );

        $calculator->setCollectorStrategyResolver($this->createCollectorResolver());

        return $calculator;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Filter\DiscountableItemFilterInterface
     */
    protected function createDiscountableItemFilter()
    {
        return new DiscountableItemFilter($this->getDiscountableItemFilterPlugins());
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface>
     */
    protected function getDiscountableItemFilterPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNTABLE_ITEM_FILTER);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationMapperInterface
     */
    protected function createDiscountStoreRelationMapper()
    {
        return new DiscountStoreRelationMapper();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\CollectorStrategyResolverInterface
     */
    protected function createCollectorResolver()
    {
        return new CollectorStrategyResolver($this->getCollectorStrategyPlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\CurrencyDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createCurrencyDecisionRule()
    {
        return new CurrencyDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\PriceModeDecisionRule|\Spryker\Zed\Discount\Business\DecisionRule\DecisionRuleInterface
     */
    public function createPriceModeDecisionRule()
    {
        return new PriceModeDecisionRule($this->createComparatorOperators());
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\CollectorStrategyPluginInterface>
     */
    protected function getCollectorStrategyPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_COLLECTOR_STRATEGY_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountEntityMapperInterface
     */
    protected function createDiscountEntityMapper()
    {
        return new DiscountEntityMapper($this->getCurrencyFacade());
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountPostCreatePluginInterface>
     */
    protected function getDiscountPostCreatePlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNT_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountPostUpdatePluginInterface>
     */
    protected function getDiscountPostUpdatePlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNT_POST_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountConfigurationExpanderPluginInterface>
     */
    protected function getConfigurationExpanderPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNT_CONFIGURATION_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\Discount\Dependency\Plugin\DiscountApplicableFilterPluginInterface>
     */
    protected function getDiscountApplicableFilterPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_DISCOUNT_APPLICABLE_FILTER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToCurrencyInterface
     */
    public function getCurrencyFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToStoreFacadeInterface
     */
    public function getStoreFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationWriterInterface
     */
    protected function createDiscountStoreRelationWriter()
    {
        return new DiscountStoreRelationWriter(
            $this->getQueryContainer(),
            $this->createDiscountStoreRelationReader(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationReaderInterface
     */
    protected function createDiscountStoreRelationReader()
    {
        return new DiscountStoreRelationReader(
            $this->getQueryContainer(),
            $this->createDiscountStoreRelationMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QuoteChangeObserver\QuoteChangeObserverInterface
     */
    public function createQuoteChangeObserver(): QuoteChangeObserverInterface
    {
        return new QuoteChangeObserver($this->getMessengerFacade());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformerInterface
     */
    public function createDiscountableItemTransformer(): DiscountableItemTransformerInterface
    {
        return new DiscountableItemTransformer(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QuoteDiscountValidator\QuoteDiscountMaxUsageValidator
     */
    public function createQuoteVoucherDiscountMaxUsageValidator(): QuoteDiscountMaxUsageValidator
    {
        return new QuoteDiscountMaxUsageValidator(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Filter\CollectedDiscountItemFilterInterface
     */
    public function createCollectedDiscountItemFilter(): CollectedDiscountItemFilterInterface
    {
        return new CollectedDiscountItemFilter();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Sorter\CollectedDiscountSorterInterface
     */
    public function createCollectedDiscountSorter(): CollectedDiscountSorterInterface
    {
        return new CollectedDiscountSorter(
            $this->getRepository(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Creator\DiscountCreateAggregatorInterface
     */
    public function createDiscountCreateAggregator(): DiscountCreateAggregatorInterface
    {
        return new DiscountCreateAggregator(
            $this->createDiscountCreateDiscountConfiguratorValidatorComposite(),
            $this->createDiscountCreator(),
            $this->createDiscountAmountCreator(),
            $this->createDiscountStoreCreator(),
            $this->getDiscountPostCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Creator\DiscountCreatorInterface
     */
    public function createDiscountCreator(): DiscountCreatorInterface
    {
        return new DiscountCreator(
            $this->createDiscountMapper(),
            $this->getEntityManager(),
            $this->createDiscountVoucherPoolCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Creator\DiscountAmountCreatorInterface
     */
    public function createDiscountAmountCreator(): DiscountAmountCreatorInterface
    {
        return new DiscountAmountCreator(
            $this->getEntityManager(),
            $this->createDiscountMapper(),
            $this->getCalculatorPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Creator\DiscountStoreCreatorInterface
     */
    public function createDiscountStoreCreator(): DiscountStoreCreatorInterface
    {
        return new DiscountStoreCreator($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Creator\DiscountVoucherPoolCreatorInterface
     */
    public function createDiscountVoucherPoolCreator(): DiscountVoucherPoolCreatorInterface
    {
        return new DiscountVoucherPoolCreator($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Updater\DiscountUpdateAggregatorInterface
     */
    public function createDiscountUpdateAggregator(): DiscountUpdateAggregatorInterface
    {
        return new DiscountUpdateAggregator(
            $this->createDiscountUpdateDiscountConfiguratorValidatorComposite(),
            $this->createDiscountUpdater(),
            $this->createDiscountAmountUpdater(),
            $this->createDiscountStoreUpdater(),
            $this->getDiscountPostUpdatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Updater\DiscountUpdaterInterface
     */
    public function createDiscountUpdater(): DiscountUpdaterInterface
    {
        return new DiscountUpdater(
            $this->createDiscountMapper(),
            $this->getEntityManager(),
            $this->createDiscountVoucherPoolUpdater(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Updater\DiscountVoucherPoolUpdaterInterface
     */
    public function createDiscountVoucherPoolUpdater(): DiscountVoucherPoolUpdaterInterface
    {
        return new DiscountVoucherPoolUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Updater\DiscountAmountUpdaterInterface
     */
    public function createDiscountAmountUpdater(): DiscountAmountUpdaterInterface
    {
        return new DiscountAmountUpdater(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createDiscountMapper(),
            $this->getCalculatorPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Updater\DiscountStoreUpdaterInterface
     */
    public function createDiscountStoreUpdater(): DiscountStoreUpdaterInterface
    {
        return new DiscountStoreUpdater(
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Mapper\DiscountMapperInterface
     */
    public function createDiscountMapper(): DiscountMapperInterface
    {
        return new DiscountMapper();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface
     */
    public function createDiscountCreateDiscountConfiguratorValidatorComposite(): DiscountConfiguratorValidatorInterface
    {
        return new DiscountConfiguratorValidatorComposite([
            $this->createDiscountConfiguratorPeriodValidator(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface
     */
    public function createDiscountUpdateDiscountConfiguratorValidatorComposite(): DiscountConfiguratorValidatorInterface
    {
        return new DiscountConfiguratorValidatorComposite([
            $this->createDiscountConfiguratorDiscountExistsValidator(),
            $this->createDiscountConfiguratorPeriodValidator(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface
     */
    public function createDiscountConfiguratorPeriodValidator(): DiscountConfiguratorValidatorInterface
    {
        return new DiscountConfiguratorPeriodValidator(
            $this->getValidationAdapter()->createValidator(),
            $this->createDiscountConfiguratorPeriodConstraintProvider(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Validator\DiscountConfiguratorValidatorInterface
     */
    public function createDiscountConfiguratorDiscountExistsValidator(): DiscountConfiguratorValidatorInterface
    {
        return new DiscountConfiguratorDiscountExistsValidator(
            $this->getRepository(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Validator\ConstraintProvider\DiscountConfiguratorConstraintProviderInterface
     */
    public function createDiscountConfiguratorPeriodConstraintProvider(): DiscountConfiguratorConstraintProviderInterface
    {
        return new DiscountConfiguratorPeriodConstraintProvider();
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\External\DiscountToValidationAdapterInterface
     */
    public function getValidationAdapter(): DiscountToValidationAdapterInterface
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::ADAPTER_VALIDATION);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): DiscountToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return array<\Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountVoucherApplyCheckerStrategyPluginInterface>
     */
    public function getDiscountVoucherApplyCheckerStrategyPlugins(): array
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGINS_DISCOUNT_VOUCHER_APPLY_CHECKER_STRATEGY);
    }
}
