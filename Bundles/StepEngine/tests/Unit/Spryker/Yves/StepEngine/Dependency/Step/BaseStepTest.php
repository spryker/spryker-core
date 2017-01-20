<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\StepEngine\Dependency\Step;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Unit\Spryker\Yves\StepEngine\Dependency\Step\Fixtures\BaseStep;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group StepEngine
 * @group Dependency
 * @group Step
 * @group BaseStepTest
 */
class BaseStepTest extends PHPUnit_Framework_TestCase
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
        $this->assertSame([], $baseStep->getTemplateVariables($this->getDataTransferMock()));
    }

    /**
     * @return \Unit\Spryker\Yves\StepEngine\Dependency\Step\Fixtures\BaseStep
     */
    private function getBaseStepInstance()
    {
        return new BaseStep(self::STEP_ROUTE, self::ESCAPE_ROUTE);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    private function getDataTransferMock()
    {
        return $this->getMockBuilder(AbstractTransfer::class)->getMock();
    }

}
