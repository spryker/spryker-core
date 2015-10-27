<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\DiscountBusiness;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Calculator\Fixed;
use SprykerFeature\Zed\Discount\Business\Calculator\Percentage;
use SprykerFeature\Zed\Discount\Business\Collector\Aggregate;
use SprykerFeature\Zed\Discount\Business\Collector\Item;
use SprykerFeature\Zed\Discount\Business\Collector\ItemExpense;
use SprykerFeature\Zed\Discount\Business\Collector\Expense;
use SprykerFeature\Zed\Discount\Business\Collector\ItemProductOption;
use SprykerFeature\Zed\Discount\Business\Distributor\Distributor;
use SprykerFeature\Zed\Discount\Business\Model\Calculator;
use SprykerFeature\Zed\Discount\Business\Model\CartRuleInterface;
use SprykerFeature\Zed\Discount\Business\Model\VoucherCodeInterface;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountCollectorWriter;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountWriter;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountVoucherWriter;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountVoucherPoolCategoryWriter;
use SprykerFeature\Zed\Discount\Business\Writer\DiscountVoucherPoolWriter;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Discount\Business\DecisionRule\Voucher;
use SprykerFeature\Zed\Discount\Business\Writer\VoucherCodesWriter;
use SprykerFeature\Zed\Discount\DiscountConfig;
use SprykerFeature\Zed\Discount\DiscountDependencyProvider;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Business\DecisionRule\MinimumCartSubtotal;
use SprykerFeature\Zed\Discount\Business\Model\Discount;
use SprykerFeature\Zed\Discount\Business\Model\VoucherEngine;
use SprykerFeature\Zed\Discount\Business\Model\CalculatorInterface;
use SprykerFeature\Zed\Discount\Business\Collector\CollectorInterface;
use SprykerFeature\Zed\Discount\Business\Model\DecisionRuleEngine;

/**
 * @method DiscountBusiness getFactory()
 * @method DiscountConfig getConfig()
 * @method DiscountQueryContainer getQueryContainer()
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
    public function createDiscount(CalculableInterface $container)
    {
        return $this->getFactory()->createModelDiscount(
            $container,
            $this->getQueryContainer(),
            $this->createDecisionRule(),
            $this->getConfig(),
            $this->createCalculator(),
            $this->createDistributor()
        );
    }

    /**
     * @return CartRuleInterface
     */
    public function createCartRule()
    {
        $store = $this->getProvidedDependency(DiscountDependencyProvider::STORE_CONFIG);

        return $this->getFactory()->createModelCartRule(
            $this->getQueryContainer(),
            $store,
            $this->createDiscountDecisionRuleWriter(),
            $this->createDiscountWriter(),
            $this->createDiscountCollectorWriter()
        );
    }

    /**
     * @return DiscountCollectorWriter
     */
    public function createDiscountCollectorWriter()
    {
        return $this->getFactory()->createWriterDiscountCollectorWriter($this->getQueryContainer());
    }

    /**
     * @return CalculatorInterface[]
     */
    public function createAvailableCalculatorPlugins()
    {
        return $this->getConfig()->getAvailableCalculatorPlugins();
    }

    /**
     * @return CollectorInterface[]
     */
    public function createAvailableCollectorPlugins()
    {
        return $this->getConfig()->getAvailableCollectorPlugins();
    }

    /**
     * @return Percentage
     */
    public function createCalculatorPercentage()
    {
        return $this->getFactory()->createCalculatorPercentage();
    }

    /**
     * @return Fixed
     */
    public function createCalculatorFixed()
    {
        return $this->getFactory()->createCalculatorFixed();
    }

    /**
     * @return DiscountWriter
     */
    public function createDiscountWriter()
    {
        return $this->getFactory()->createWriterDiscountWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return VoucherPoolCategory
     */
    public function createVoucherPoolCategory()
    {
        return $this->getFactory()->createModelVoucherPoolCategory(
            $this->getQueryContainer()
        );
    }

    /**
     * @return VoucherCodesWriter
     */
    public function createVoucherCodesWriter()
    {
        return $this->getFactory()->createWriterVoucherCodesWriter(
            $this->getQueryContainer(),
            $this->createDiscountWriter(),
            $this->createDiscountVoucherPoolWriter(),
            $this->createDiscountVoucherPoolCategoryWriter(),
            $this->createDiscountDecisionRuleWriter(),
            $this->createDiscountCollectorWriter()
        );
    }

    /**
     * @return DiscountDecisionRuleWriter
     */
    public function createDiscountDecisionRuleWriter()
    {
        return $this->getFactory()->createWriterDiscountDecisionRuleWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return DiscountVoucherWriter
     */
    public function createDiscountVoucherWriter()
    {
        return $this->getFactory()->createWriterDiscountVoucherWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return DiscountVoucherPoolWriter
     */
    public function createDiscountVoucherPoolWriter()
    {
        return $this->getFactory()->createWriterDiscountVoucherPoolWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return DiscountVoucherPoolCategoryWriter
     */
    public function createDiscountVoucherPoolCategoryWriter()
    {
        return $this->getFactory()->createWriterDiscountVoucherPoolCategoryWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return DecisionRuleEngine
     */
    protected function createDecisionRule()
    {
        return $this->getFactory()->createModelDecisionRuleEngine();
    }

    /**
     * @return Calculator
     */
    protected function createCalculator()
    {
        return $this->getFactory()->createModelCalculator();
    }

    /**
     * @return Distributor
     */
    public function createDistributor()
    {
        return $this->getFactory()->createDistributorDistributor();
    }

    /**
     * @return VoucherEngine
     */
    public function createVoucherEngine()
    {
        return $this->getFactory()->createModelVoucherEngine(
            $this->getConfig(),
            $this->getQueryContainer(),
            $this->getProvidedDependency(DiscountDependencyProvider::FLASH_MESSENGER),
            $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

    /**
     * @return Item
     */
    public function createItemCollector()
    {
        return $this->getFactory()->createCollectorItem();
    }

    /**
     * @return ItemExpense
     */
    public function createItemExpenseCollector()
    {
        return $this->getFactory()->createCollectorItemExpense();
    }

    /**
     * @return Expense
     */
    public function createOrderExpenseCollector()
    {
        return $this->getFactory()->createCollectorExpense();
    }

    /**
     * @return ItemProductOption
     */
    public function createItemProductOptionCollector()
    {
        return $this->getFactory()->createCollectorItemProductOption();
    }

    /**
     * @return Aggregate
     */
    public function createAggregateCollector()
    {
        return $this->getFactory()
            ->createCollectorAggregate([
                $this->createItemCollector(),
                $this->createItemProductOptionCollector(),
                $this->createItemExpenseCollector(),
                $this->createOrderExpenseCollector(),
            ]
        );
    }

    /**
     * @return VoucherCodeInterface
     */
    public function createVoucherCode()
    {
        return $this->getFactory()->createModelVoucherCode($this->getQueryContainer());
    }

}
