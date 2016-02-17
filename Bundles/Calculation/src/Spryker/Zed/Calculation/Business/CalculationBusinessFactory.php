<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business;

use Spryker\Zed\Calculation\Business\Model\CheckoutGrandTotalPrecondition;
use Spryker\Zed\Calculation\Business\Model\StackExecutor;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseGrossSumAmountCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossAmountsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionGrossSumCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Calculation\CalculationDependencyProvider;

/**
 * @method \Spryker\Zed\Calculation\CalculationConfig getConfig()
 */
class CalculationBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\StackExecutor
     */
    public function createStackExecutor()
    {
        return new StackExecutor($this->getProvidedCalculatorStack());
    }

    /**
     * @return \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[]
     */
    protected function getProvidedCalculatorStack()
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::CALCULATOR_STACK);
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseGrossSumAmountCalculator
     */
    public function createExpenseGrossSumAmount()
    {
        return new ExpenseGrossSumAmountCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator
     */
    public function createExpenseTotalsCalculator()
    {
        return new ExpenseTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator
     */
    public function createGrandTotalsCalculator()
    {
        return new GrandTotalTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossAmountsCalculator
     */
    public function createItemGrossSumCalculator()
    {
        return new ItemGrossAmountsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionGrossSumCalculator
     */
    public function createOptionGrossSumCalculator()
    {
        return new ProductOptionGrossSumCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator
     */
    public function createRemoveTotalsCalculator()
    {
        return new RemoveTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator
     */
    public function createSubtotalTotalsCalculator()
    {
        return new SubtotalTotalsCalculator();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\CheckoutGrandTotalPrecondition
     */
    public function createCheckoutGrandTotalPrecondition()
    {
        return new CheckoutGrandTotalPrecondition($this->createStackExecutor());
    }

}
