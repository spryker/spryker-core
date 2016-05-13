<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Yves\StepEngine\Process\Steps;

use Spryker\Yves\StepEngine\Process\Steps\StepCollection;
use Spryker\Yves\StepEngine\Process\Steps\StepCollectionInterface;
use Spryker\Yves\StepEngine\Process\Steps\StepInterface;

/**
 * @group Spryker
 * @group Yves
 * @group StepEngine
 * @group StepCollection
 */
class StepCollectionTest extends \PHPUnit_Framework_TestCase
{

    public function testAddStep()
    {
        $stepCollection = new StepCollection();
        $stepMock = $this->getStepMock();

        $result = $stepCollection->addStep($stepMock);

        $this->assertInstanceOf(StepCollectionInterface::class, $result);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\StepEngine\Process\Steps\StepInterface
     */
    private function getStepMock()
    {
        return $this->getMock(StepInterface::class);
    }

}
