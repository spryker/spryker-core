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
use Spryker\Zed\Calculation\CalculationConfig;
use Spryker\Zed\Calculation\CalculationDependencyProvider;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

/**
 * @method CalculationConfig getConfig()
 */
class CalculationBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return StackExecutor
     */
    public function createStackExecutor()
    {
        return new StackExecutor($this->getProvidedCalculatorStack());
    }

    /**
     * @return CalculatorPluginInterface[]
     */
    protected function getProvidedCalculatorStack()
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::CALCULATOR_STACK);
    }

    /**
     * @return ExpenseGrossSumAmountCalculator
     */
    public function createExpenseGrossSumAmount()
    {
        return new ExpenseGrossSumAmountCalculator();
    }

    /**
     * @return ExpenseTotalsCalculator
     */
    public function createExpenseTotalsCalculator()
    {
        return new ExpenseTotalsCalculator();
    }

    /**
     * @return GrandTotalTotalsCalculator
     */
    public function createGrandTotalsCalculator()
    {
        return new GrandTotalTotalsCalculator();
    }

    /**
     * @return ItemGrossAmountsCalculator
     */
    public function createItemGrossSumCalculator()
    {
        return new ItemGrossAmountsCalculator();
    }

    /**
     * @return ProductOptionGrossSumCalculator
     */
    public function createOptionGrossSumCalculator()
    {
        return new ProductOptionGrossSumCalculator();
    }

    /**
     * @return RemoveTotalsCalculator
     */
    public function createRemoveTotalsCalculator()
    {
        return new RemoveTotalsCalculator();
    }

    /**
     * @return SubtotalTotalsCalculator
     */
    public function createSubtotalTotalsCalculator()
    {
        return new SubtotalTotalsCalculator();
    }

    /**
     * @return CheckoutGrandTotalPrecondition
     */
    public function createCheckoutGrandTotalPrecondition()
    {
        return new CheckoutGrandTotalPrecondition($this->createStackExecutor());
    }

}
