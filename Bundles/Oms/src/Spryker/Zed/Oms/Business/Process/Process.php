<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Process;

use Exception;
use Spryker\Zed\Oms\Business\Util\DrawerInterface;

class Process implements ProcessInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Spryker\Zed\Oms\Business\Process\StateInterface[]
     */
    protected $states = [];

    /**
     * @var \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
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
     * @var \Spryker\Zed\Oms\Business\Util\DrawerInterface
     */
    protected $drawer;

    /**
     * @var \Spryker\Zed\Oms\Business\Process\ProcessInterface[]
     */
    protected $subProcesses = [];

    /**
     * @param \Spryker\Zed\Oms\Business\Util\DrawerInterface $drawer
     */
    public function __construct(DrawerInterface $drawer)
    {
        $this->drawer = $drawer;
    }

    /**
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return string
     */
    public function draw($highlightState = null, $format = null, $fontSize = null)
    {
        return $this->drawer->draw($this, $highlightState, $format, $fontSize);
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface[] $subProcesses
     *
     * @return void
     */
    public function setSubProcesses($subProcesses)
    {
        $this->subProcesses = $subProcesses;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface[]
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
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $subProcess
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
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     *
     * @return void
     */
    public function setStates($states)
    {
        $this->states = $states;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
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
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
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
     * @param string $stateId
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    public function getStateFromAllProcesses($stateId)
    {
        $processes = $this->getAllProcesses();
        foreach ($processes as $process) {
            if ($process->hasState($stateId)) {
                return $process->getState($stateId);
            }
        }
        throw new Exception('Unknown state: ' . $stateId);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface[]
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
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     *
     * @return void
     */
    public function addTransition(TransitionInterface $transition)
    {
        $this->transitions[] = $transition;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface[] $transitions
     *
     * @return void
     */
    public function setTransitions($transitions)
    {
        $this->transitions = $transitions;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
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
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface[]
     */
    public function getAllStates()
    {
        $states = [];
        if ($this->hasStates()) {
            $states = $this->getStates();
        }
        if ($this->hasSubProcesses()) {
            foreach ($this->getSubProcesses() as $subProcess) {
                if ($subProcess->hasStates()) {
                    $states = array_merge($states, $subProcess->getStates());
                }
            }
        }

        return $states;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface[]
     */
    public function getAllReservedStates()
    {
        $reservedStates = [];
        $states = $this->getAllStates();
        foreach ($states as $state) {
            if ($state->isReserved()) {
                $reservedStates[] = $state;
            }
        }

        return $reservedStates;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
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
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
     */
    public function getAllTransitionsWithoutEvent()
    {
        $transitions = [];
        $allTransitions = $this->getAllTransitions();
        foreach ($allTransitions as $transition) {
            if ($transition->hasEvent() === false) {
                $transitions[] = $transition;
            }
        }

        return $transitions;
    }

    /**
     * Gets all manual and all on enter events as manually executable ones.
     *
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface[]
     */
    public function getManualEvents()
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
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface[][]
     */
    public function getManualEventsBySource()
    {
        $events = $this->getManualEvents();

        $eventsBySource = [];
        foreach ($events as $event) {
            $transitions = $event->getTransitions();
            foreach ($transitions as $transition) {
                $sourceName = $transition->getSource()->getName();
                if (!isset($eventsBySource[$sourceName])) {
                    $eventsBySource[$sourceName] = [];
                }
                if (!in_array($event->getName(), $eventsBySource[$sourceName])) {
                    $eventsBySource[$sourceName][] = $event->getName();
                }
            }
        }

        return $eventsBySource;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface[]
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
        return isset($this->file);
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
}
