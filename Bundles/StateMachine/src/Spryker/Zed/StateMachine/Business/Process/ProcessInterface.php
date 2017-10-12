<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Process;

interface ProcessInterface
{
    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $subProcesses
     *
     * @return void
     */
    public function setSubProcesses($subProcesses);

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    public function getSubProcesses();

    /**
     * @return bool
     */
    public function hasSubProcesses();

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $subProcess
     *
     * @return void
     */
    public function addSubProcess(ProcessInterface $subProcess);

    /**
     * @param mixed $main
     *
     * @return void
     */
    public function setMain($main);

    /**
     * @return mixed
     */
    public function getMain();

    /**
     * @param mixed $name
     *
     * @return void
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface[] $states
     *
     * @return void
     */
    public function setStates($states);

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $state
     *
     * @return void
     */
    public function addState(StateInterface $state);

    /**
     * @param string $stateId
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    public function getState($stateId);

    /**
     * @param string $stateId
     *
     * @return bool
     */
    public function hasState($stateId);

    /**
     * @param string $stateName
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    public function getStateFromAllProcesses($stateName);

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface[]
     */
    public function getStates();

    /**
     * @return bool
     */
    public function hasStates();

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
     *
     * @return void
     */
    public function addTransition(TransitionInterface $transition);

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $transitions
     *
     * @return void
     */
    public function setTransitions($transitions);

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getTransitions();

    /**
     * @return bool
     */
    public function hasTransitions();

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface[]
     */
    public function getAllStates();

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getAllTransitions();

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getAllTransitionsWithoutEvent();

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface[]
     */
    public function getManualEvents();

    /**
     * @return array
     */
    public function getManualEventsBySource();

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    public function getAllProcesses();

    /**
     * @param mixed $file
     *
     * @return void
     */
    public function setFile($file);

    /**
     * @return bool
     */
    public function hasFile();

    /**
     * @return mixed
     */
    public function getFile();
}
