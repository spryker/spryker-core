<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Process;

interface ProcessInterface
{

    /**
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return bool
     */
    public function draw($highlightState = null, $format = null, $fontSize = null);

    /**
     * @param ProcessInterface[] $subProcesses
     *
     * @return void
     */
    public function setSubProcesses($subProcesses);

    /**
     * @return ProcessInterface[]
     */
    public function getSubProcesses();

    /**
     * @return bool
     */
    public function hasSubProcesses();

    /**
     * @param ProcessInterface $subProcess
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
     * @param StateInterface[] $states
     *
     * @return void
     */
    public function setStates($states);

    /**
     * @param StateInterface $state
     *
     * @return void
     */
    public function addState(StateInterface $state);

    /**
     * @param string $stateId
     *
     * @return StateInterface
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
     * @return StateInterface
     */
    public function getStateFromAllProcesses($stateId);

    /**
     * @return StateInterface[]
     */
    public function getStates();

    /**
     * @return bool
     */
    public function hasStates();

    /**
     * @param TransitionInterface $transition
     *
     * @return void
     */
    public function addTransition(TransitionInterface $transition);

    /**
     * @param TransitionInterface[] $transitions
     *
     * @return void
     */
    public function setTransitions($transitions);

    /**
     * @return TransitionInterface[]
     */
    public function getTransitions();

    /**
     * @return bool
     */
    public function hasTransitions();

    /**
     * @return StateInterface[]
     */
    public function getAllStates();

    /**
     * @return StateInterface[]
     */
    public function getAllReservedStates();

    /**
     * @return TransitionInterface[]
     */
    public function getAllTransitions();

    /**
     * @return TransitionInterface[]
     */
    public function getAllTransitionsWithoutEvent();

    /**
     * @return EventInterface[]
     */
    public function getManualEvents();

    /**
     * @return EventInterface[]
     */
    public function getManualEventsBySource();

    /**
     * @return ProcessInterface[]
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
