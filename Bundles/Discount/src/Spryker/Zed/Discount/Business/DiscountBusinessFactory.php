<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\DiscountBusiness;
use Spryker\Zed\Discount\Business\Calculator\Fixed;
use Spryker\Zed\Discount\Business\Calculator\Percentage;
use Spryker\Zed\Discount\Business\Collector\Aggregate;
use Spryker\Zed\Discount\Business\Collector\Item;
use Spryker\Zed\Discount\Business\Collector\ItemExpense;
use Spryker\Zed\Discount\Business\Collector\Expense;
use Spryker\Zed\Discount\Business\Collector\ItemProductOption;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\Model\Calculator;
use Spryker\Zed\Discount\Business\Model\CartRuleInterface;
use Spryker\Zed\Discount\Business\Model\CollectorResolver;
use Spryker\Zed\Discount\Business\Model\VoucherCodeInterface;
use Spryker\Zed\Discount\Business\Writer\DiscountCollectorWriter;
use Spryker\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter;
use Spryker\Zed\Discount\Business\Writer\DiscountWriter;
use Spryker\Zed\Discount\Business\Writer\DiscountVoucherWriter;
use Spryker\Zed\Discount\Business\Writer\DiscountVoucherPoolCategoryWriter;
use Spryker\Zed\Discount\Business\Writer\DiscountVoucherPoolWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Discount\Business\DecisionRule\Voucher;
use Spryker\Zed\Discount\Business\Writer\VoucherCodesWriter;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Spryker\Zed\Discount\Business\DecisionRule\MinimumCartSubtotal;
use Spryker\Zed\Discount\Business\Model\Discount;
use Spryker\Zed\Discount\Business\Model\VoucherEngine;
use Spryker\Zed\Discount\Business\Model\DecisionRuleEngine;
use Spryker\Zed\Messenger\Business\MessengerFacade;


/**
 * @method DiscountConfig getConfig()
 * @method DiscountQueryContainer getQueryContainer()
 */
class DiscountBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return Voucher
     */
    public function getDecisionRuleVoucher()
    {
        return new Voucher($this->getQueryContainer());
    }

    /**
     * @return MinimumCartSubtotal
     */
    public function getDecisionRuleMinimumCartSubtotal()
    {
        return new MinimumCartSubtotal();
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return Discount
     */
    public function createDiscount(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createModelDiscount(
            $quoteTransfer,
            $this->getQueryContainer(),
            $this->createDecisionRule(),
            $this->createCalculator(),
            $this->createDistributor(),
            $this->getMessengerFacade(),
            $this->getDecisionRulePlugins()
        );
    }

    /**
     * @return CartRuleInterface
     */
    public function createCartRule()
    {
        return new CartRule(
            $this->getQueryContainer(),
            $this->getStoreConfig(),
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
        return new DiscountCollectorWriter($this->getQueryContainer());
    }

    /**
     * @return Percentage
     */
    public function createCalculatorPercentage()
    {
        return new Percentage();
    }

    /**
     * @return Fixed
     */
    public function createCalculatorFixed()
    {
        return new Fixed();
    }

    /**
     * @return DiscountWriter
     */
    public function createDiscountWriter()
    {
        return new DiscountWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return VoucherPoolCategory
     */
    public function createVoucherPoolCategory()
    {
        return new VoucherPoolCategory(
            $this->getQueryContainer()
        );
    }

    /**
     * @return VoucherCodesWriter
     */
    public function createVoucherCodesWriter()
    {
        return new VoucherCodesWriter(
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
        return new DiscountDecisionRuleWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return DiscountVoucherWriter
     */
    public function createDiscountVoucherWriter()
    {
        return new DiscountVoucherWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return DiscountVoucherPoolWriter
     */
    public function createDiscountVoucherPoolWriter()
    {
        return new DiscountVoucherPoolWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return DiscountVoucherPoolCategoryWriter
     */
    public function createDiscountVoucherPoolCategoryWriter()
    {
        return new DiscountVoucherPoolCategoryWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return DecisionRuleEngine
     */
    protected function createDecisionRuleEngine()
    {
        return new DecisionRuleEngine();
    }

    /**
     * @return Calculator
     */
    protected function createCalculator()
    {
        return $this->getFactory()->createModelCalculator(
            $this->createCollectorResolver(),
            $this->getCalculatorPlugins()
        );
    }

    /**
     * @return Distributor
     */
    public function createDistributor()
    {
        return new Distributor();
    }

    /**
     * @return VoucherEngine
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
     * @return Item
     */
    public function createItemCollector()
    {
        return new Item();
    }

    /**
     * @return Expense
     */
    public function createOrderExpenseCollector()
    {
        return new Expense();
    }

    /**
     * @return ItemProductOption
     */
    public function createItemProductOptionCollector()
    {
        return new ItemProductOption();
    }

    /**
     * @return Aggregate
     */
    public function createAggregateCollector()
    {
        return new Aggregate([
                $this->createItemCollector(),
                $this->createItemProductOptionCollector(),
                $this->createOrderExpenseCollector(),
            ]
        );
    }

    /**
     * @return VoucherCodeInterface
     */
    public function createVoucherCode()
    {
        return new VoucherCode($this->getQueryContainer());
    }

    /**
     * @return CollectorResolver
     */
    public function createCollectorResolver()
    {
        return $this->getFactory()->createModelCollectorResolver($this->getCollectorPlugins());
    }

    /**
     * @return DiscountDecisionRulePluginInterface[]
     */
    public function getDecisionRulePlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::DECISION_RULE_PLUGINS);
    }

    /**
     * @return DiscountCalculatorPluginInterface[]
     */
    public function getCalculatorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::CALCULATOR_PLUGINS);
    }

    /**
     * @return DiscountCollectorPluginInterface[]
     */
    public function getCollectorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::COLLECTOR_PLUGINS);
    }

    /**
     * @return MessengerFacade
     */
    protected function getMessengerFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_MESSENGER);
    }

}
