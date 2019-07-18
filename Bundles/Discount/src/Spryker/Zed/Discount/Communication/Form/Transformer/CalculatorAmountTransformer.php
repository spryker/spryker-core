<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Transformer;

use Spryker\Zed\Discount\Business\Exception\CalculatorException;
use Symfony\Component\Form\DataTransformerInterface;

class CalculatorAmountTransformer implements DataTransformerInterface
{
    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    protected $calculatorPlugins = [];

    /**
     * @param array $calculatorPlugins
     */
    public function __construct(array $calculatorPlugins)
    {
        $this->calculatorPlugins = $calculatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountCalculatorTransfer|null $value
     *
     * @return \Generated\Shared\Transfer\DiscountCalculatorTransfer|null
     */
    public function transform($value)
    {
        if (!$this->isValueSet($value)) {
            return null;
        }

        $calculatorPlugin = $this->getCalculatorPlugin($value->getCalculatorPlugin());
        $transformedAmount = $calculatorPlugin->transformFromPersistence((int)$value->getAmount());
        $value->setAmount($transformedAmount);

        return $value;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountCalculatorTransfer|null $value
     *
     * @return \Generated\Shared\Transfer\DiscountCalculatorTransfer|null
     */
    public function reverseTransform($value)
    {
        if (!$this->isValueSet($value)) {
            return null;
        }

        $calculatorPlugin = $this->getCalculatorPlugin($value->getCalculatorPlugin());
        $transformedAmount = $calculatorPlugin->transformForPersistence((float)$value->getAmount());
        $value->setAmount($transformedAmount);

        return $value;
    }

    /**
     * @param string $pluginName
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\CalculatorException
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    protected function getCalculatorPlugin($pluginName)
    {
        if (isset($this->calculatorPlugins[$pluginName])) {
            return $this->calculatorPlugins[$pluginName];
        }

        throw new CalculatorException(sprintf(
            'Calculator plugin with name "%s" not found. 
            Have you added it to DiscountDependencyProvider::getAvailableCalculatorPlugins plugin stack?',
            $pluginName
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountCalculatorTransfer|null $value
     *
     * @return bool
     */
    protected function isValueSet($value)
    {
        return $value && $value->getCalculatorPlugin();
    }
}
