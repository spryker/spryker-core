<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Yves\StepEngine\Process\Steps;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\StepEngine\Process\Steps\StepInterface;
use Unit\Spryker\Yves\StepEngine\Process\Steps\Fixtures\BaseStep;

/**
 * @group Spryker
 * @group Yves
 * @group StepEngine
 * @group BaseStep
 */
class BaseStepTest extends \PHPUnit_Framework_TestCase
{

    const STEP_ROUTE = 'stepRoute';
    const ESCAPE_ROUTE = 'escapeRoute';

    /**
     * @return void
     */
    public function testInstantiation()
    {
        $baseStep = $this->getBaseStepInstance();

        $this->assertInstanceOf(StepInterface::class, $baseStep);
    }

    /**
     * @return void
     */
    public function testGetStepRoute()
    {
        $baseStep = $this->getBaseStepInstance();
        $this->assertSame(self::STEP_ROUTE, $baseStep->getStepRoute());
    }

    /**
     * @return void
     */
    public function testGetEscapeRoute()
    {
        $baseStep = $this->getBaseStepInstance();
        $this->assertSame(self::ESCAPE_ROUTE, $baseStep->getEscapeRoute());
    }

    /**
     * @return void
     */
    public function testGetTemplateVariables()
    {
        $baseStep = $this->getBaseStepInstance();
        $this->assertSame([], $baseStep->getTemplateVariables());
    }

    /**
     * @return void
     */
    public function testIsCartEmptyReturnTrue()
    {
        $baseStep = $this->getBaseStepInstance();

        $this->assertTrue($baseStep->isCartEmpty(new QuoteTransfer()));
    }

    /**
     * @return void
     */
    public function testIsCartEmptyReturnFalse()
    {
        $baseStep = $this->getBaseStepInstance();

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem(new ItemTransfer());

        $this->assertFalse($baseStep->isCartEmpty($quoteTransfer));
    }

    /**
     * @return \Unit\Spryker\Yves\StepEngine\Process\Steps\Fixtures\BaseStep
     */
    private function getBaseStepInstance()
    {
        return new BaseStep(self::STEP_ROUTE, self::ESCAPE_ROUTE);
    }

}
