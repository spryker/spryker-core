<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Process;

interface ProcessInterface
{
    /**
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return string
     */
    public function draw($highlightState = null, $format = null, $fontSize = null);

    /**
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $subProcesses
     *
     * @return void
     */
    public function setSubProcesses($subProcesses);

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\ProcessInterface>
     */
    public function getSubProcesses();

    /**
     * @return bool
     */
    public function hasSubProcesses();

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $subProcess
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
     * @param mixed $name
     *
     * @return void
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array<\Spryker\Zed\Oms\Business\Process\StateInterface> $states
     *
     * @return void
     */
    public function setStates($states);

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     *
     * @return void
     */
    public function addState(StateInterface $state);

    /**
     * @param string $stateId
     *
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    public function getState($stateId);

    /**
     * @param string $stateId
     *
     * @return bool
     */
    public function hasState($stateId);

    /**
     * @param string $stateId
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    public function getStateFromAllProcesses($stateId);

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\StateInterface>
     */
    public function getStates();

    /**
     * @return bool
     */
    public function hasStates();

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     *
     * @return void
     */
    public function addTransition(TransitionInterface $transition);

    /**
     * @param array<\Spryker\Zed\Oms\Business\Process\TransitionInterface> $transitions
     *
     * @return void
     */
    public function setTransitions($transitions);

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\TransitionInterface>
     */
    public function getTransitions();

    /**
     * @return bool
     */
    public function hasTransitions();

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\StateInterface>
     */
    public function getAllStates();

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\StateInterface>
     */
    public function getAllReservedStates();

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\TransitionInterface>
     */
    public function getAllTransitions();

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\TransitionInterface>
     */
    public function getAllTransitionsWithoutEvent();

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\EventInterface>
     */
    public function getManualEvents();

    /**
     * @return array<array<string>>
     */
    public function getManualEventsBySource();

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\ProcessInterface>
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
