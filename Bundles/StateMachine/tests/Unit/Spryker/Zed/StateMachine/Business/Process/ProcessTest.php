<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\StateMachine\Business\StateMachine;

use Spryker\Zed\StateMachine\Business\Process\Event;
use Spryker\Zed\StateMachine\Business\Process\Process;
use Spryker\Zed\StateMachine\Business\Process\Transition;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group StateMachine
 * @group ProcessTest
 */
class ProcessTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testManualEvents()
    {
        $process = $this->getProcessMock();

        $transitions = $this->getTransitions();
        $process->expects($this->once())->method('getAllTransitions')->willReturn($transitions);

        $result = $process->getManualEvents();
        $this->assertSame(2, count($result));

        $this->assertSame('manual', $result[0]->getName());
        $this->assertSame('onenter', $result[1]->getName());
    }

    /**
     * @return array
     */
    protected function getTransitions()
    {
        $transitions = [];

        $transition = new Transition();
        $event = new Event();
        $event->setName('default');
        $transition->setEvent($event);
        $transitions[] = $transition;

        $transition = new Transition();
        $event = new Event();
        $event->setName('manual');
        $event->setManual(true);
        $transition->setEvent($event);
        $transitions[] = $transition;

        $transition = new Transition();
        $event = new Event();
        $event->setName('onenter');
        $event->setOnEnter(true);
        $transition->setEvent($event);
        $transitions[] = $transition;

        return $transitions;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\StateMachine\Business\Process\Process
     */
    protected function getProcessMock()
    {
        return $this->getMockBuilder(Process::class)->setMethods(['getAllTransitions'])->getMock();
    }

}
