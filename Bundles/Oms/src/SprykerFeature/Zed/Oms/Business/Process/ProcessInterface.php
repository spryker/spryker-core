<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Process;

interface ProcessInterface
{

    /**
     * @param string $highlightState
     * @param null $format
     * @param int $fontsize
     *
     * @return bool
     */
    public function draw($highlightState = null, $format = null, $fontsize = null);

    /**
     * @param ProcessInterface[] $subprocesses
     */
    public function setSubprocesses($subprocesses);

    /**
     * @return ProcessInterface[]
     */
    public function getSubprocesses();

    /**
     * @return bool
     */
    public function hasSubprocesses();

    /**
     * @param ProcessInterface $subprocess
     */
    public function addSubprocess(ProcessInterface $subprocess);

    /**
     * @param mixed $main
     */
    public function setMain($main);

    /**
     * @return mixed
     */
    public function getMain();

    /**
     * @param mixed $name
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param StateInterface[] $states
     */
    public function setStates($states);

    /**
     * @param StateInterface $state
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
     */
    public function addTransition(TransitionInterface $transition);

    /**
     * @param TransitionInterface[] $transitions
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
