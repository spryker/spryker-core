<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Calculation\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Business\Model\StackExecutor;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Model
 * @group StackExecutorTest
 */
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
            ->getMockBuilder('Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface')
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
     * @param \Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface[] $calculatorStack
     *
     * @return \Spryker\Zed\Calculation\Business\Model\StackExecutor
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
