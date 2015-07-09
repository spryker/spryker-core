<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business;

use SprykerFeature\Zed\Calculation\Business\Model\StackExecutor;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ExpensePriceToPayCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\GrandTotalTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\ItemPriceToPayCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\OptionPriceToPayCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\RemoveAllExpensesCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\RemoveTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\SubtotalWithoutItemExpensesTotalsCalculator;
use SprykerFeature\Zed\Calculation\Business\Model\Calculator\TaxTotalsCalculator;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Calculation\CalculationConfig;
use Generated\Zed\Ide\FactoryAutoCompletion\CalculationBusiness;

/**
 * @method CalculationBusiness getFactory()
 * @method CalculationConfig getConfig()
 */
class CalculationDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return StackExecutor
     */
    public function getStackExecutor()
    {
        return $this->getFactory()->createModelStackExecutor();
    }

    /**
     * @return ExpensePriceToPayCalculator
     */
    public function getExpensePriceToPayCalculator()
    {
        return $this->getFactory()->createModelCalculatorExpensePriceToPayCalculator();
    }

    /**
     * @return ExpenseTotalsCalculator
     */
    public function getExpenseTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorExpenseTotalsCalculator();
    }

    /**
     * @return GrandTotalTotalsCalculator
     */
    public function getGrandTotalsCalculator()
    {
        $subtotalTotalsCalculator = $this->getFactory()->createModelCalculatorSubtotalTotalsCalculator();
        $expenseTotalsCalculator = $this->getFactory()->createModelCalculatorExpenseTotalsCalculator();

        $grandTotalsCalculator = $this->getFactory()
            ->createModelCalculatorGrandTotalTotalsCalculator(
                $subtotalTotalsCalculator,
                $expenseTotalsCalculator
            );

        return $grandTotalsCalculator;
    }

    /**
     * @return ItemPriceToPayCalculator
     */
    public function getItemPriceToPayCalculator()
    {
        return $this->getFactory()->createModelCalculatorItemPriceToPayCalculator();
    }

    /**
     * @return OptionPriceToPayCalculator
     */
    public function getOptionPriceToPayCalculator()
    {
        return $this->getFactory()->createModelCalculatorOptionPriceToPayCalculator();
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
    public function getRemoveTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorRemoveTotalsCalculator();
    }

    /**
     * @return SubtotalTotalsCalculator
     */
    public function getSubtotalTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorSubtotalTotalsCalculator();
    }

    /**
     * @return SubtotalWithoutItemExpensesTotalsCalculator
     */
    public function getSubtotalWithoutItemExpensesTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorSubtotalWithoutItemExpensesTotalsCalculator();
    }

    /**
     * @return TaxTotalsCalculator
     */
    public function getTaxTotalsCalculator()
    {
        return $this->getFactory()->createModelCalculatorTaxTotalsCalculator(
            $this->getFactory()->createModelPriceCalculationHelper()
        );
    }

}
