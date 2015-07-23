<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business;

use Generated\Shared\Discount\OrderInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\DiscountBusiness;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
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
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Discount\Business\DecisionRule\Voucher;
use SprykerFeature\Zed\Discount\DiscountConfig;
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
 * @method DiscountConfig getConfig()
 */
class DiscountDependencyContainer extends AbstractBusinessDependencyContainer
{

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
     * @param CalculableInterface $container
     *
     * @return Discount
     */
    public function getDiscount(CalculableInterface $container)
    {
        return $this->getFactory()->createModelDiscount(
            $container,
            $this->getQueryContainer(),
            $this->getDecisionRule(),
            $this->getConfig(),
            $this->getCalculator(),
            $this->getDistributor()
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
        return $this->getConfig()->getAvailableDecisionRulePlugins();
    }

    /**
     * @return CalculatorInterface[]
     */
    public function getAvailableCalculatorPlugins()
    {
        return $this->getConfig()->getAvailableCalculatorPlugins();
    }

    /**
     * @return CollectorInterface[]
     */
    public function getAvailableCollectorPlugins()
    {
        return $this->getConfig()->getAvailableCollectorPlugins();
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
            $this->getConfig(),
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
