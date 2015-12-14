<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business;

use Spryker\Zed\Calculation\Business\Model\StackExecutor;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseGrossSumAmountCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ItemGrossAmountsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\ProductOptionGrossSumCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveAllExpensesCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;
use Spryker\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Calculation\CalculationConfig;
use Generated\Zed\Ide\FactoryAutoCompletion\CalculationBusiness;
use Spryker\Zed\Calculation\CalculationDependencyProvider;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

/**
 * @method CalculationBusiness getFactory()
 * @method CalculationConfig getConfig()
 */
class CalculationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return StackExecutor
     */
    public function createStackExecutor()
    {
        return $this->getFactory()->createModelStackExecutor($this->getProvidedCalculatorStack());
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
        return $this->getFactory()->createModelCalculatorExpenseGrossSumAmountCalculator();
    }

    /**
     * @return ExpenseTotalsCalculator
     */
    public function createExpenseTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorExpenseTotalsCalculator();
    }

    /**
     * @return GrandTotalTotalsCalculator
     */
    public function createGrandTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorGrandTotalTotalsCalculator();
    }

    /**
     * @return ItemGrossAmountsCalculator
     */
    public function createItemGrossSumCalculator()
    {
        return $this->getFactory()->createModelCalculatorItemGrossAmountsCalculator();
    }

    /**
     * @return ProductOptionGrossSumCalculator
     */
    public function createOptionGrossSumCalculator()
    {
        return $this->getFactory()->createModelCalculatorProductOptionGrossSumCalculator();
    }

    /**
     * @return RemoveAllExpensesCalculator
     */
    public function getRemoveAllExpensesCalculator()
    {
        return $this->getFactory()->createModelCalculatorRemoveAllExpensesCalculator();
    }

    /**
     * @return RemoveTotalsCalculator
     */
    public function createRemoveTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorRemoveTotalsCalculator();
    }

    /**
     * @return SubtotalTotalsCalculator
     */
    public function createSubtotalTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorSubtotalTotalsCalculator();
    }

}
