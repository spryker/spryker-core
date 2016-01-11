<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Discount\Business\Calculator\Fixed;
use Spryker\Zed\Discount\Business\Calculator\Percentage;
use Spryker\Zed\Discount\Business\Collector\Aggregate;
use Spryker\Zed\Discount\Business\Collector\Item;
use Spryker\Zed\Discount\Business\Collector\Expense;
use Spryker\Zed\Discount\Business\Collector\ItemProductOption;
use Spryker\Zed\Discount\Business\Distributor\Distributor;
use Spryker\Zed\Discount\Business\Model\Calculator;
use Spryker\Zed\Discount\Business\Model\CartRule;
use Spryker\Zed\Discount\Business\Model\CartRuleInterface;
use Spryker\Zed\Discount\Business\Model\CollectorResolver;
use Spryker\Zed\Discount\Business\Model\DiscountOrderSaver;
use Spryker\Zed\Discount\Business\Model\DiscountSaverInterface;
use Spryker\Zed\Discount\Business\Model\VoucherCode;
use Spryker\Zed\Discount\Business\Model\VoucherCodeInterface;
use Spryker\Zed\Discount\Business\Model\VoucherPoolCategory;
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
use Spryker\Shared\Kernel\Store;

/**
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainer getQueryContainer()
 */
class DiscountBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\Voucher
     */
    public function getDecisionRuleVoucher()
    {
        return new Voucher($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DecisionRule\MinimumCartSubtotal
     */
    public function getDecisionRuleMinimumCartSubtotal()
    {
        return new MinimumCartSubtotal();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Discount\Business\Model\Discount
     */
    public function createDiscount(QuoteTransfer $quoteTransfer)
    {
        return new Discount(
            $quoteTransfer,
            $this->getQueryContainer(),
            $this->createDecisionRuleEngine(),
            $this->createCalculator(),
            $this->createDistributor(),
            $this->getMessengerFacade(),
            $this->getDecisionRulePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Model\CartRuleInterface
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
     * @return \Spryker\Zed\Discount\Business\Writer\DiscountCollectorWriter
     */
    public function createDiscountCollectorWriter()
    {
        return new DiscountCollectorWriter($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\Percentage
     */
    public function createCalculatorPercentage()
    {
        return new Percentage();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\Fixed
     */
    public function createCalculatorFixed()
    {
        return new Fixed();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Writer\DiscountWriter
     */
    public function createDiscountWriter()
    {
        return new DiscountWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Model\VoucherPoolCategory
     */
    public function createVoucherPoolCategory()
    {
        return new VoucherPoolCategory(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Writer\VoucherCodesWriter
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
     * @return \Spryker\Zed\Discount\Business\Writer\DiscountDecisionRuleWriter
     */
    public function createDiscountDecisionRuleWriter()
    {
        return new DiscountDecisionRuleWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Writer\DiscountVoucherWriter
     */
    public function createDiscountVoucherWriter()
    {
        return new DiscountVoucherWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Writer\DiscountVoucherPoolWriter
     */
    public function createDiscountVoucherPoolWriter()
    {
        return new DiscountVoucherPoolWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Writer\DiscountVoucherPoolCategoryWriter
     */
    public function createDiscountVoucherPoolCategoryWriter()
    {
        return new DiscountVoucherPoolCategoryWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Model\DecisionRuleEngine
     */
    protected function createDecisionRuleEngine()
    {
        return new DecisionRuleEngine();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Model\Calculator
     */
    protected function createCalculator()
    {
        return new Calculator(
            $this->createCollectorResolver(),
            $this->getMessengerFacade(),
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
     * @return \Spryker\Zed\Discount\Business\Model\VoucherEngine
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
     * @return \Spryker\Zed\Discount\Business\Collector\Item
     */
    public function createItemCollector()
    {
        return new Item();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\Expense
     */
    public function createOrderExpenseCollector()
    {
        return new Expense();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\ItemProductOption
     */
    public function createItemProductOptionCollector()
    {
        return new ItemProductOption();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\Aggregate
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
     * @return \Spryker\Zed\Discount\Business\Model\VoucherCodeInterface
     */
    public function createVoucherCode()
    {
        return new VoucherCode($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Model\CollectorResolver
     */
    public function createCollectorResolver()
    {
        return new CollectorResolver($this->getCollectorPlugins());
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[]
     */
    public function getDecisionRulePlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::DECISION_RULE_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Model\DiscountSaverInterface
     */
    public function createDiscountSaver()
    {
        return new DiscountOrderSaver(
            $this->getQueryContainer(),
            $this->createVoucherCode()
        );
    }


    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    public function getCalculatorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::CALCULATOR_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface[]
     */
    public function getCollectorPlugins()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::COLLECTOR_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\MessengerFacade
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

}
