<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Spryker\Zed\Discount\Communication\Form\CalculatorForm;

class CalculatorFormDataProvider
{

    /**
     * @var array
     */
    protected $calculatorPlugins;

    /**
     * @param array $calculatorPlugins
     */
    public function __construct(array $calculatorPlugins)
    {
        $this->calculatorPlugins = $calculatorPlugins;
    }

    /**
     * @return string[]
     */
    public function getData()
    {
        return [
            CalculatorForm::FIELD_CALCULATOR_PLUGIN => $this->getCalculatorPlugins(),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @return array
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

}
