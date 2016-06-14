<?php

namespace Spryker\Zed\Discount\Communication\Form\Transformer;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Symfony\Component\Form\DataTransformerInterface;

class CalculatorAmountTransformer implements DataTransformerInterface
{

    /**
     * @var DiscountCalculatorPluginInterface[]
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
     * @param DiscountCalculatorTransfer|null $value
     *
     * @return DiscountCalculatorTransfer|null
     */
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        $calculatorPlugin = $this->getCalculatorPlugin($value->getCalculatorPlugin());
        $transformedAmount = $calculatorPlugin->transformFromPersistence($value->getAmount());
        $value->setAmount($transformedAmount);

        return $value;
    }

    /**
     * @param DiscountCalculatorTransfer|null $value
     *
     * @return DiscountCalculatorTransfer|null
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            return null;
        }

        $calculatorPlugin = $this->getCalculatorPlugin($value->getCalculatorPlugin());
        $transformedAmount = $calculatorPlugin->transformForPersistence($value->getAmount());
        $value->setAmount($transformedAmount);

        return $value;
    }

    /**
     * @param string $pluginName
     *
     * @return DiscountCalculatorPluginInterface
     */
    protected function getCalculatorPlugin($pluginName)
    {
        if (isset($this->calculatorPlugins[$pluginName])) {
            return $this->calculatorPlugins[$pluginName];
        }

        throw new \InvalidArgumentException(sprintf(
            'Calculator plugin with name "%s" not found',
            $pluginName
        ));
    }
}
