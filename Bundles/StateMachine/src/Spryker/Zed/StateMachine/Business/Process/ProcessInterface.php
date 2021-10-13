<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Process;

interface ProcessInterface
{
    /**
     * @param array<\Spryker\Zed\StateMachine\Business\Process\ProcessInterface> $subProcesses
     *
     * @return void
     */
    public function setSubProcesses($subProcesses);

    /**
     * @return array<\Spryker\Zed\StateMachine\Business\Process\ProcessInterface>
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
     * @param bool $isMain
     *
     * @return void
     */
    public function setIsMain($isMain);

    /**
     * @return bool
     */
    public function getIsMain();

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array<\Spryker\Zed\StateMachine\Business\Process\StateInterface> $states
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
     * @return array<\Spryker\Zed\StateMachine\Business\Process\StateInterface>
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
     * @param array<\Spryker\Zed\StateMachine\Business\Process\TransitionInterface> $transitions
     *
     * @return void
     */
    public function setTransitions($transitions);

    /**
     * @return array<\Spryker\Zed\StateMachine\Business\Process\TransitionInterface>
     */
    public function getTransitions();

    /**
     * @return bool
     */
    public function hasTransitions();

    /**
     * @return array<\Spryker\Zed\StateMachine\Business\Process\StateInterface>
     */
    public function getAllStates();

    /**
     * @return array<\Spryker\Zed\StateMachine\Business\Process\TransitionInterface>
     */
    public function getAllTransitions();

    /**
     * @return array<\Spryker\Zed\StateMachine\Business\Process\TransitionInterface>
     */
    public function getAllTransitionsWithoutEvent();

    /**
     * @return array<\Spryker\Zed\StateMachine\Business\Process\EventInterface>
     */
    public function getManuallyExecutableEvents();

    /**
     * @return array<string[]>
     */
    public function getManuallyExecutableEventsBySource();

    /**
     * @return array<\Spryker\Zed\StateMachine\Business\Process\ProcessInterface>
     */
    public function getAllProcesses();

    /**
     * @param string $file
     *
     * @return void
     */
    public function setFile($file);

    /**
     * @return bool
     */
    public function hasFile();

    /**
     * @return string
     */
    public function getFile();
}
