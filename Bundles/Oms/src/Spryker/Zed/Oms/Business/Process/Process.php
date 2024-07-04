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
     * @var array<\Spryker\Zed\Oms\Business\Process\StateInterface>
     */
    protected $states = [];

    /**
     * @var array<\Spryker\Zed\Oms\Business\Process\TransitionInterface>
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
     * @var array<\Spryker\Zed\Oms\Business\Process\ProcessInterface>
     */
    protected $subProcesses = [];

    /**
     * @var array<string, \Spryker\Zed\Oms\Business\Process\StateInterface>|null
     */
    protected ?array $processStates = null;

    /**
     * @var array<\Spryker\Zed\Oms\Business\Process\StateInterface>|null
     */
    protected ?array $allStates = null;

    /**
     * @var array<\Spryker\Zed\Oms\Business\Process\StateInterface>|null
     */
    protected ?array $allReservedStates = null;

    /**
     * @var array<\Spryker\Zed\Oms\Business\Process\TransitionInterface>|null
     */
    protected ?array $allTransitions = null;

    /**
     * @var array<\Spryker\Zed\Oms\Business\Process\TransitionInterface>|null
     */
    protected ?array $allTransitionsWithoutEvent = null;

    /**
     * @var array<\Spryker\Zed\Oms\Business\Process\EventInterface>|null
     */
    protected ?array $manualEvents = null;

    /**
     * @var array<string, array<string>>|null
     */
    protected ?array $manualEventsBySource = null;

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
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $subProcesses
     *
     * @return void
     */
    public function setSubProcesses($subProcesses)
    {
        $this->subProcesses = $subProcesses;
    }

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\ProcessInterface>
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
     * @param array<\Spryker\Zed\Oms\Business\Process\StateInterface> $states
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
        if ($this->processStates !== null && isset($this->processStates[$stateId])) {
            return $this->processStates[$stateId];
        }

        $processes = $this->getAllProcesses();
        foreach ($processes as $process) {
            if ($process->hasState($stateId)) {
                $this->processStates[$stateId] = $process->getState($stateId);

                return $this->processStates[$stateId];
            }
        }

        throw new Exception('Unknown state: ' . $stateId);
    }

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\StateInterface>
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
     * @param array<\Spryker\Zed\Oms\Business\Process\TransitionInterface> $transitions
     *
     * @return void
     */
    public function setTransitions($transitions)
    {
        $this->transitions = $transitions;
    }

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\TransitionInterface>
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
     * @return array<\Spryker\Zed\Oms\Business\Process\StateInterface>
     */
    public function getAllStates()
    {
        if ($this->allStates !== null) {
            return $this->allStates;
        }

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

        return $this->allStates = $states;
    }

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\StateInterface>
     */
    public function getAllReservedStates()
    {
        if ($this->allReservedStates !== null) {
            return $this->allReservedStates;
        }

        $reservedStates = [];
        $states = $this->getAllStates();
        foreach ($states as $state) {
            if ($state->isReserved()) {
                $reservedStates[] = $state;
            }
        }

        return $this->allReservedStates = $reservedStates;
    }

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\TransitionInterface>
     */
    public function getAllTransitions()
    {
        if ($this->allTransitions !== null) {
            return $this->allTransitions;
        }

        $transitions = [];
        if ($this->hasTransitions()) {
            $transitions = $this->getTransitions();
        }
        foreach ($this->getSubProcesses() as $subProcess) {
            if ($subProcess->hasTransitions()) {
                $transitions = array_merge($transitions, $subProcess->getTransitions());
            }
        }

        return $this->allTransitions = $transitions;
    }

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\TransitionInterface>
     */
    public function getAllTransitionsWithoutEvent()
    {
        if ($this->allTransitionsWithoutEvent !== null) {
            return $this->allTransitionsWithoutEvent;
        }

        $transitions = [];
        $allTransitions = $this->getAllTransitions();
        foreach ($allTransitions as $transition) {
            if ($transition->hasEvent() === false) {
                $transitions[] = $transition;
            }
        }

        return $this->allTransitionsWithoutEvent = $transitions;
    }

    /**
     * Gets all manual and all on enter events as manually executable ones.
     *
     * @return array<\Spryker\Zed\Oms\Business\Process\EventInterface>
     */
    public function getManualEvents()
    {
        if ($this->manualEvents !== null) {
            return $this->manualEvents;
        }

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

        return $this->manualEvents = $manuallyExecutableEventList;
    }

    /**
     * @return array<array<string>>
     */
    public function getManualEventsBySource()
    {
        if ($this->manualEventsBySource !== null) {
            return $this->manualEventsBySource;
        }

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

        return $this->manualEventsBySource = $eventsBySource;
    }

    /**
     * @return array<\Spryker\Zed\Oms\Business\Process\ProcessInterface>
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

    /**
     * @return $this
     */
    public function warmupCache()
    {
        $allStates = $this->getAllStates();
        foreach ($allStates as $state) {
            $this->getStateFromAllProcesses($state->getName());
        }
        $this->getAllReservedStates();
        $this->getAllTransitions();
        $this->getAllTransitionsWithoutEvent();
        $this->getManualEvents();
        $this->getManualEventsBySource();

        return $this;
    }
}
