<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Process;

interface StateInterface
{
    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $incomingTransitions
     *
     * @return $this
     */
    public function setIncomingTransitions(array $incomingTransitions);

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getIncomingTransitions();

    /**
     * @return bool
     */
    public function hasIncomingTransitions();

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $outgoingTransitions
     *
     * @return $this
     */
    public function setOutgoingTransitions(array $outgoingTransitions);

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getOutgoingTransitions();

    /**
     * @return bool
     */
    public function hasOutgoingTransitions();

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\EventInterface $event
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getOutgoingTransitionsByEvent(EventInterface $event);

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface[]
     */
    public function getEvents();

    /**
     * @param string $eventName
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface
     */
    public function getEvent($eventName);

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasEvent($id);

    /**
     * @return bool
     */
    public function hasAnyEvent();

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
     *
     * @return void
     */
    public function addIncomingTransition(TransitionInterface $transition);

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
     *
     * @return void
     */
    public function addOutgoingTransition(TransitionInterface $transition);

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     *
     * @return $this
     */
    public function setProcess($process);

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface
     */
    public function getProcess();

    /**
     * @return bool
     */
    public function hasOnEnterEvent();

    /**
     * @throws \Exception
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface
     */
    public function getOnEnterEvent();

    /**
     * @return bool
     */
    public function hasTimeoutEvent();

    /**
     * @throws \Exception
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface[]
     */
    public function getTimeoutEvents();

    /**
     * @param string $flag
     *
     * @return $this
     */
    public function addFlag($flag);

    /**
     * @param string $flag
     *
     * @return bool
     */
    public function hasFlag($flag);

    /**
     * @return bool
     */
    public function hasFlags();

    /**
     * @return array
     */
    public function getFlags();

    /**
     * @return string
     */
    public function getDisplay();

    /**
     * @param string $display
     *
     * @return $this
     */
    public function setDisplay($display);
}
