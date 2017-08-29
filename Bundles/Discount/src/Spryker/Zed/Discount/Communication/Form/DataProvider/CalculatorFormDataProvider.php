<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Communication\Form\CalculatorForm;

class CalculatorFormDataProvider extends BaseDiscountFormDataProvider
{

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    protected $calculatorPlugins;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[] $calculatorPlugins
     */
    public function __construct(array $calculatorPlugins)
    {
        $this->calculatorPlugins = $calculatorPlugins;

        $this->setDefaults();
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    protected function getCalculatorPlugins()
    {
        $calculatorPlugins = array_combine(array_keys($this->calculatorPlugins), array_keys($this->calculatorPlugins));

        foreach ($calculatorPlugins as $key => $calculatorPlugin) {
            $calculatorPlugin = strtolower($calculatorPlugin);
            $calculatorPlugin = str_replace('plugin', '', $calculatorPlugin);
            $calculatorPlugin = trim(str_replace('_', ' ', $calculatorPlugin));
            $calculatorPlugins[$key] = ucfirst($calculatorPlugin);
        }

        return $calculatorPlugins;
    }

    /**
     * @return void
     */
    protected function setDefaults()
    {
        $this->options = [
            CalculatorForm::OPTION_COLLECTOR_TYPE_CHOICES => [
                DiscountConstants::DISCOUNT_COLLECTOR_STRATEGY_QUERY_STRING => 'Query String',
            ],
        ];

        $this->data = [
            CalculatorForm::FIELD_CALCULATOR_PLUGIN => $this->getCalculatorPlugins(),
        ];
    }

}
