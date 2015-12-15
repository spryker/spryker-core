<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\StackExecutor;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

class StackExecutorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testStackExecutorShouldInvokReCalculateOnCalculationPlugin()
    {
        $quoteTransfer = $this->quoteTransfer();
        $calculatorStack = $this->createCalculatorStack($quoteTransfer);

        $stackExecutor = $this->createStackExecutor($calculatorStack);
        $stackExecutor->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function createCalculatorStack(QuoteTransfer $quoteTransfer)
    {
        $calculatorStack = [];

        $calculatorPluginMock = $this
            ->getMockBuilder('SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $calculatorPluginMock
            ->expects($this->once())
            ->method('recalculate')
            ->with($quoteTransfer);

        $calculatorStack[] = $calculatorPluginMock;

        return $calculatorStack;
    }

    /**
     * @param \SprykerFeature\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[] $calculatorStack
     *
     * @return \SprykerFeature\Zed\Calculation\Business\Model\StackExecutor
     */
    protected function createStackExecutor(array $calculatorStack)
    {
        return new StackExecutor($calculatorStack);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function quoteTransfer()
    {
        return new QuoteTransfer();
    }

}
