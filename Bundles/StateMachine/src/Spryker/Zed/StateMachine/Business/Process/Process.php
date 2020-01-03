<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Process;

use Spryker\Zed\StateMachine\Business\Exception\StateMachineException;

class Process implements ProcessInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\StateInterface[]
     */
    protected $states = [];

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    protected $transitions = [];

    /**
     * @var bool
     */
    protected $isMain = false;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    protected $subProcesses = [];

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $subProcesses
     *
     * @return void
     */
    public function setSubProcesses($subProcesses)
    {
        $this->subProcesses = $subProcesses;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    public function getSubProcesses()
    {
        return $this->subProcesses;
    }

    /**
     * @return bool
     */
    public function hasSubProcesses()
    {
        return count($this->subProcesses) > 0;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $subProcess
     *
     * @return void
     */
    public function addSubProcess(ProcessInterface $subProcess)
    {
        $this->subProcesses[] = $subProcess;
    }

    /**
     * @param bool $isMain
     *
     * @return void
     */
    public function setIsMain($isMain)
    {
        $this->isMain = $isMain;
    }

    /**
     * @return bool
     */
    public function getIsMain()
    {
        return $this->isMain;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface[] $states
     *
     * @return void
     */
    public function setStates($states)
    {
        $this->states = $states;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $state
     *
     * @return void
     */
    public function addState(StateInterface $state)
    {
        $this->states[$state->getName()] = $state;
    }

    /**
     * @param string $stateId
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    public function getState($stateId)
    {
        return $this->states[$stateId];
    }

    /**
     * @param string $stateId
     *
     * @return bool
     */
    public function hasState($stateId)
    {
        return array_key_exists($stateId, $this->states);
    }

    /**
     * @param string $stateName
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineException
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    public function getStateFromAllProcesses($stateName)
    {
        $processes = $this->getAllProcesses();
        foreach ($processes as $process) {
            if ($process->hasState($stateName)) {
                return $process->getState($stateName);
            }
        }

        throw new StateMachineException(
            sprintf(
                'State "%s" not found in any of state machine processes. Is state defined in xml definition file?',
                $stateName
            )
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface[]
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @return bool
     */
    public function hasStates()
    {
        return (bool)$this->states;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
     *
     * @return void
     */
    public function addTransition(TransitionInterface $transition)
    {
        $this->transitions[] = $transition;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $transitions
     *
     * @return void
     */
    public function setTransitions($transitions)
    {
        $this->transitions = $transitions;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    /**
     * @return bool
     */
    public function hasTransitions()
    {
        return (bool)$this->transitions;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface[]
     */
    public function getAllStates()
    {
        $states = [];
        if ($this->hasStates()) {
            $states = $this->getStates();
        }

        if (!$this->hasSubProcesses()) {
            return $states;
        }

        foreach ($this->getSubProcesses() as $subProcess) {
            if (!$subProcess->hasStates()) {
                continue;
            }
            $states = array_merge($states, $subProcess->getStates());
        }

        return $states;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getAllTransitions()
    {
        $transitions = [];
        if ($this->hasTransitions()) {
            $transitions = $this->getTransitions();
        }
        foreach ($this->getSubProcesses() as $subProcess) {
            if ($subProcess->hasTransitions()) {
                $transitions = array_merge($transitions, $subProcess->getTransitions());
            }
        }

        return $transitions;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getAllTransitionsWithoutEvent()
    {
        $transitions = [];
        $allTransitions = $this->getAllTransitions();
        foreach ($allTransitions as $transition) {
            if ($transition->hasEvent() === true) {
                continue;
            }
            $transitions[] = $transition;
        }

        return $transitions;
    }

    /**
     * Gets all "manual" and "on enter" events as manually executable ones.
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface[]
     */
    public function getManuallyExecutableEvents()
    {
        $manuallyExecutableEventList = [];
        $transitions = $this->getAllTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                $event = $transition->getEvent();
                if ($event->isManual() || $event->isOnEnter()) {
                    $manuallyExecutableEventList[] = $event;
                }
            }
        }

        return $manuallyExecutableEventList;
    }

    /**
     * @return string[][]
     */
    public function getManuallyExecutableEventsBySource()
    {
        $events = $this->getManuallyExecutableEvents();

        $eventsBySource = [];
        foreach ($events as $event) {
            $transitions = $event->getTransitions();
            $eventsBySource = $this->groupTransitionsBySourceName(
                $transitions,
                $eventsBySource,
                $event
            );
        }

        return $eventsBySource;
    }

    /**
     * @param array $transitions
     * @param array $eventsBySource
     * @param \Spryker\Zed\StateMachine\Business\Process\EventInterface $event
     *
     * @return string[][]
     */
    protected function groupTransitionsBySourceName(array $transitions, array $eventsBySource, EventInterface $event)
    {
        foreach ($transitions as $transition) {
            $sourceName = $transition->getSourceState()->getName();
            if (!isset($eventsBySource[$sourceName])) {
                $eventsBySource[$sourceName] = [];
            }
            if (!in_array($event->getName(), $eventsBySource[$sourceName], true)) {
                $eventsBySource[$sourceName][] = $event->getName();
            }
        }

        return $eventsBySource;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    public function getAllProcesses()
    {
        $processes = [];
        $processes[] = $this;
        $processes = array_merge($processes, $this->getSubProcesses());

        return $processes;
    }

    /**
     * @param string $file
     *
     * @return void
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return bool
     */
    public function hasFile()
    {
        return $this->file !== null;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
}
