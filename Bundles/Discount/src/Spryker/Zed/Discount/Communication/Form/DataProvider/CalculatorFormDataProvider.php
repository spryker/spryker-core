<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Communication\Form\CalculatorForm;
use Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormExpanderPluginInterface;

class CalculatorFormDataProvider
{

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    protected $calculatorPlugins;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[] $calculatorPlugins
     */
    public function __construct(array $calculatorPlugins)
    {
        $this->calculatorPlugins = $calculatorPlugins;

        $this->setDefaults();
    }

    /**
     * @return string[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormExpanderPluginInterface[] $formExpanderPlugins
     *
     * @return void
     */
    public function applyCalculatorFormExpanderPlugins(array $formExpanderPlugins)
    {
        foreach ($formExpanderPlugins as $calculatorFormExpanderPlugin) {
            if ($calculatorFormExpanderPlugin->getFormTypeToExtend() !== DiscountFormExpanderPluginInterface::FORM_TYPE_CALCULATION) {
                continue;
            }
            $this->data = $calculatorFormExpanderPlugin->expandDataProviderData($this->data);
            $this->options = $calculatorFormExpanderPlugin->expandDataProviderOptions($this->options);
        }
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
