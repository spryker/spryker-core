<?php

namespace SprykerFeature\Zed\Calculation\Business;

use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * Class CalculationSettings
 * @package SprykerFeature\Zed\Calculation\Business
 */
class CalculationSettings
{
    /**
     * @var Locator|\Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @param Locator $locator
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return CalculatorPluginInterface[]|TotalsCalculatorPluginInterface[]
     */
    public function getCalculatorStack()
    {
        return [
            $this->locator->calculation()->pluginRemoveTotalsCalculatorPlugin(),
            $this->locator->calculation()->pluginRemoveAllExpensesCalculatorPlugin(),
            $this->locator->calculation()->pluginExpenseTotalsCalculatorPlugin(),
            $this->locator->calculation()->pluginSubtotalTotalsCalculatorPlugin(),
            $this->locator->calculation()->pluginSubtotalWithoutItemExpensesTotalsCalculatorPlugin(),
            $this->locator->calculation()->pluginGrandTotalTotalsCalculatorPlugin(),
            $this->locator->calculation()->pluginExpensePriceToPayCalculatorPlugin(),
            $this->locator->calculation()->pluginItemPriceToPayCalculatorPlugin(),
            $this->locator->calculation()->pluginOptionPriceToPayCalculatorPlugin(),
            $this->locator->calculation()->pluginGrandTotalTotalsCalculatorPlugin(),
            $this->locator->calculation()->pluginTaxTotalsCalculatorPlugin(),
        ];
    }

    /**
     * @return CalculatorPluginInterface[]|TotalsCalculatorPluginInterface[]
     * @deprecated?
     */
    public function getSoftCalculatorStack()
    {
        return [
        ];
    }
}
