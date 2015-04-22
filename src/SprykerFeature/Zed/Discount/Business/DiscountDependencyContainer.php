<?php

namespace SprykerFeature\Zed\Discount\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\DiscountBusiness;
use SprykerFeature\Zed\Discount\Business\Calculator\Fixed;
use SprykerFeature\Zed\Discount\Business\Calculator\Percentage;
use SprykerFeature\Zed\Discount\Business\Collector\Item;
use SprykerFeature\Zed\Discount\Business\Collector\ItemExpense;
use SprykerFeature\Zed\Discount\Business\Collector\Expense;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountWriter;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountVoucherWriter;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountVoucherPoolCategoryWriter;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountVoucherPoolWriter;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Discount\Business\DecisionRule\Voucher;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Business\DecisionRule\MinimumCartSubtotal;
use SprykerFeature\Zed\Discount\Business\Model\Discount;
use SprykerFeature\Zed\Discount\Business\Model\VoucherEngine;
use SprykerFeature\Zed\Discount\Business\Model\CalculatorInterface;
use SprykerFeature\Zed\Discount\Business\Collector\CollectorInterface;
use SprykerFeature\Zed\Discount\Business\Model\Distributor;
use SprykerFeature\Zed\Discount\Business\Model\Calculator;
use SprykerFeature\Zed\Discount\Business\Model\DecisionRuleEngine;

/**
 * @method DiscountBusiness getFactory()
 */
class DiscountDependencyContainer extends AbstractDependencyContainer
{
    const PLUGIN_DECISION_RULE_VOUCHER = 'PLUGIN_DECISION_RULE_VOUCHER';
    const PLUGIN_COLLECTOR_ITEM = 'PLUGIN_COLLECTOR_ITEM';
    const PLUGIN_COLLECTOR_ORDER_EXPENSE = 'PLUGIN_COLLECTOR_ORDER_EXPENSE';
    const PLUGIN_COLLECTOR_ITEM_EXPENSE = 'PLUGIN_COLLECTOR_ITEM_EXPENSE';
    const PLUGIN_CALCULATOR_PERCENTAGE = 'PLUGIN_CALCULATOR_PERCENTAGE';
    const PLUGIN_CALCULATOR_FIXED = 'PLUGIN_CALCULATOR_FIXED';

    /**
     * @return Voucher
     */
    public function getDecisionRuleVoucher()
    {
        return $this->getFactory()->createDecisionRuleVoucher(
            $this->getQueryContainer()
        );
    }

    /**
     * @return MinimumCartSubtotal
     */
    public function getDecisionRuleMinimumCartSubtotal()
    {
        return $this->getFactory()->createDecisionRuleMinimumCartSubtotal();
    }

    /**
     * @param DiscountableContainerInterface $container
     * @return Discount
     */
    public function getDiscount(DiscountableContainerInterface $container)
    {
        return $this->getFactory()->createModelDiscount(
            $container,
            $this->getQueryContainer(),
            $this->getDecisionRule(),
            $this->getDiscountSettings(),
            $this->getCalculator(),
            $this->getDistributor()
        );
    }

    /**
     * @return DiscountSettings
     */
    public function getDiscountSettings()
    {
        return  $this->getFactory()->createDiscountSettings(
            $this->getLocator(),
            $this->getAvailableDecisionRulePlugins(),
            $this->getAvailableCalculatorPlugins(),
            $this->getAvailableCollectorPlugins()
        );
    }

    /**
     * @return DiscountFacade
     */
    public function getDiscountFacade()
    {
        return $this->getLocator()->discount()->facade();
    }

    /**
     * @return array
     */
    public function getAvailableDecisionRulePlugins()
    {
        return [
            self::PLUGIN_DECISION_RULE_VOUCHER => $this->getLocator()->discount()->pluginDecisionRuleVoucher()
        ];
    }

    /**
     * @return CalculatorInterface[]
     */
    public function getAvailableCalculatorPlugins()
    {
        return [
            self::PLUGIN_CALCULATOR_PERCENTAGE => $this->getLocator()->discount()->pluginCalculatorPercentage(),
            self::PLUGIN_CALCULATOR_FIXED => $this->getLocator()->discount()->pluginCalculatorFixed(),
        ];
    }

    /**
     * @return CollectorInterface[]
     */
    public function getAvailableCollectorPlugins()
    {
        return [
            self::PLUGIN_COLLECTOR_ITEM => $this->getLocator()->discount()->pluginCollectorItem(),
            self::PLUGIN_COLLECTOR_ORDER_EXPENSE => $this->getLocator()->discount()->pluginCollectorOrderExpense(),
            self::PLUGIN_COLLECTOR_ITEM_EXPENSE => $this->getLocator()->discount()->pluginCollectorItemExpense(),
        ];
    }

    /**
     * @return Percentage
     */
    public function getCalculatorPercentage()
    {
        return $this->getFactory()->createCalculatorPercentage();
    }

    /**
     * @return Fixed
     */
    public function getCalculatorFixed()
    {
        return $this->getFactory()->createCalculatorFixed();
    }

    /**
     * @return DiscountWriter
     */
    public function getDiscountWriter()
    {
        return $this->getFactory()->createWriterDiscountWriter(
            $this->getLocator()
        );
    }

    /**
     * @return DiscountDecisionRuleWriter
     */
    public function getDiscountDecisionRuleWriter()
    {
        return $this->getFactory()->createWriterDiscountDecisionRuleWriter(
            $this->getLocator()
        );
    }

    /**
     * @return DiscountVoucherWriter
     */
    public function getDiscountVoucherWriter()
    {
        return $this->getFactory()->createWriterDiscountVoucherWriter(
            $this->getLocator()
        );
    }

    /**
     * @return DiscountVoucherPoolWriter
     */
    public function getDiscountVoucherPoolWriter()
    {
        return $this->getFactory()->createWriterDiscountVoucherPoolWriter(
            $this->getLocator()
        );
    }

    /**
     * @return DiscountVoucherPoolCategoryWriter
     */
    public function getDiscountVoucherPoolCategoryWriter()
    {
        return $this->getFactory()->createWriterDiscountVoucherPoolCategoryWriter(
            $this->getLocator()
        );
    }

    /**
     * @return DiscountQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->getLocator()->discount()->queryContainer();
    }

    /**
     * @return DecisionRuleEngine
     */
    protected function getDecisionRule()
    {
        return $this->getFactory()->createModelDecisionRuleEngine();
    }

    /**
     * @return Calculator
     */
    protected function getCalculator()
    {
        return $this->getFactory()->createModelCalculator();
    }

    /**
     * @return Distributor
     */
    public function getDistributor()
    {
        return $this->getFactory()->createModelDistributor(
            $this->getLocator()
        );
    }

    /**
     * @return VoucherEngine
     */
    public function getVoucherEngine()
    {
        return $this->getFactory()->createModelVoucherEngine(
            $this->getDiscountSettings(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return Item
     */
    public function getItemCollector()
    {
        return $this->getFactory()->createCollectorItem();
    }

    /**
     * @return ItemExpense
     */
    public function getItemExpenseCollector()
    {
        return $this->getFactory()->createCollectorItemExpense();
    }

    /**
     * @return Expense
     */
    public function getOrderExpenseCollector()
    {
        return $this->getFactory()->createCollectorExpense();
    }
}
