<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Business\Process;

use Codeception\Test\Unit;
use Spryker\Zed\StateMachine\Business\Process\Event;
use Spryker\Zed\StateMachine\Business\Process\Process;
use Spryker\Zed\StateMachine\Business\Process\Transition;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group Process
 * @group ProcessTest
 * Add your own group annotations below this line
 */
class ProcessTest extends Unit
{
    /**
     * @return void
     */
    public function testThatManualEventsIncludeOnEnterEvents()
    {
        $process = $this->createProcess();
        $process->setTransitions($this->getTransitionsWithManualAndOnEnterEvents());

        $result = $process->getManuallyExecutableEvents();
        $this->assertSame(2, count($result));

        $this->assertSame('manual', $result[0]->getName());
        $this->assertSame('onenter', $result[1]->getName());
    }

    /**
     * @return array
     */
    protected function getTransitionsWithManualAndOnEnterEvents()
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
     * @return \Spryker\Zed\StateMachine\Business\Process\Process
     */
    protected function createProcess()
    {
        return new Process();
    }
}
