<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business;

use Spryker\Zed\Discount\Business\Model\VoucherCode;
use Spryker\Zed\Discount\Business\Model\VoucherPoolCategory;
use Spryker\Zed\Discount\Business\Model\CartRule;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
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
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Spryker\Zed\Discount\Business\DecisionRule\MinimumCartSubtotal;
use Spryker\Zed\Discount\Business\Model\Discount;
use Spryker\Zed\Discount\Business\Model\VoucherEngine;
use Spryker\Zed\Discount\Business\Model\CalculatorInterface;
use Spryker\Zed\Discount\Business\Collector\CollectorInterface;
use Spryker\Zed\Discount\Business\Model\DecisionRuleEngine;
use Spryker\Shared\Kernel\Store;

/**
 * @method DiscountConfig getConfig()
 * @method DiscountQueryContainer getQueryContainer()
 */
class DiscountBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return Voucher
     */
    public function createDecisionRuleVoucher()
    {
        return new Voucher($this->getQueryContainer());
    }

    /**
     * @return MinimumCartSubtotal
     */
    public function createDecisionRuleMinimumCartSubtotal()
    {
        return new MinimumCartSubtotal();
    }

    /**
     * @param CalculableInterface $container
     *
     * @return Discount
     */
    public function createDiscount(CalculableInterface $container)
    {
        return new Discount(
            $container,
            $this->getQueryContainer(),
            $this->createDecisionRuleEngine(),
            $this->getConfig(),
            $this->createCalculator(),
            $this->createDistributor(),
            $this->getMessengerFacade()
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
        return new Calculator($this->createCollectorResolver(), $this->getMessengerFacade());
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
     * @return ItemExpense
     */
    public function createItemExpenseCollector()
    {
        return new ItemExpense();
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
            $this->createItemExpenseCollector(),
            $this->createOrderExpenseCollector(),
        ]);
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
        return new CollectorResolver($this->getConfig());
    }

    /**
     * @return DiscountToMessengerInterface
     */
    protected function getMessengerFacade()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return ConnectionInterface
     */
    protected function getPropelConnection()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_PROPEL_CONNECTION);
    }

    /**
     * @return Store
     */
    protected function getStoreConfig()
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::STORE_CONFIG);
    }

}
