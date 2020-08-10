<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Process;

use Exception;

class State implements StateInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $display;

    /**
     * @var bool
     */
    protected $reserved;

    /**
     * @var \Spryker\Zed\Oms\Business\Process\ProcessInterface
     */
    protected $process;

    /**
     * @var array
     */
    protected $flags = [];

    /**
     * @var \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
     */
    protected $outgoingTransitions = [];

    /**
     * @var \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
     */
    protected $incomingTransitions = [];

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface[] $incomingTransitions
     *
     * @return void
     */
    public function setIncomingTransitions(array $incomingTransitions)
    {
        $this->incomingTransitions = $incomingTransitions;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
     */
    public function getIncomingTransitions()
    {
        return $this->incomingTransitions;
    }

    /**
     * @return bool
     */
    public function hasIncomingTransitions()
    {
        return (bool)$this->incomingTransitions;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface[] $outgoingTransitions
     *
     * @return void
     */
    public function setOutgoingTransitions(array $outgoingTransitions)
    {
        $this->outgoingTransitions = $outgoingTransitions;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
     */
    public function getOutgoingTransitions()
    {
        return $this->outgoingTransitions;
    }

    /**
     * @return bool
     */
    public function hasOutgoingTransitions()
    {
        return (bool)$this->outgoingTransitions;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\EventInterface $event
     *
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
     */
    public function getOutgoingTransitionsByEvent(EventInterface $event)
    {
        $transitions = [];
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                if ($transition->getEvent()->getName() === $event->getName()) {
                    $transitions[] = $transition;
                }
            }
        }

        return $transitions;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface[]
     */
    public function getEvents()
    {
        $events = [];
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                $events[$transition->getEvent()->getName()] = $transition->getEvent();
            }
        }

        return $events;
    }

    /**
     * @param string $id
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface
     */
    public function getEvent($id)
    {
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                $event = $transition->getEvent();
                if ($event->getName() === $id) {
                    return $event;
                }
            }
        }

        throw new Exception('Event ' . $id . ' not found.');
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasEvent($id)
    {
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                $event = $transition->getEvent();
                if ($event->getName() === $id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasAnyEvent()
    {
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     *
     * @return void
     */
    public function addIncomingTransition(TransitionInterface $transition)
    {
        $this->incomingTransitions[] = $transition;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     *
     * @return void
     */
    public function addOutgoingTransition(TransitionInterface $transition)
    {
        $this->outgoingTransitions[] = $transition;
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
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     *
     * @return void
     */
    public function setProcess($process)
    {
        $this->process = $process;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param bool $reserved
     *
     * @return void
     */
    public function setReserved($reserved)
    {
        $this->reserved = $reserved;
    }

    /**
     * @return bool
     */
    public function isReserved()
    {
        return $this->reserved;
    }

    /**
     * @return bool
     */
    public function hasOnEnterEvent()
    {
        $transitions = $this->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                if ($transition->getEvent()->isOnEnter() === true) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @throws \Exception
     *
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface
     */
    public function getOnEnterEvent()
    {
        $transitions = $this->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                if ($transition->getEvent()->isOnEnter() === true) {
                    return $transition->getEvent();
                }
            }
        }

        throw new Exception('There is no onEnter event for state ' . $this->getName());
    }

    /**
     * @return bool
     */
    public function hasTimeoutEvent()
    {
        $transitions = $this->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                if ($transition->getEvent()->hasTimeout() === true) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface[]
     */
    public function getTimeoutEvents()
    {
        $events = [];

        $transitions = $this->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                if ($transition->getEvent()->hasTimeout() === true) {
                    $events[] = $transition->getEvent();
                }
            }
        }

        return $events;
    }

    /**
     * @param string $flag
     *
     * @return void
     */
    public function addFlag($flag)
    {
        $this->flags[] = $flag;
    }

    /**
     * @param string $flag
     *
     * @return bool
     */
    public function hasFlag($flag)
    {
        return in_array($flag, $this->flags);
    }

    /**
     * @return bool
     */
    public function hasFlags()
    {
        return count($this->flags) > 0;
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @param string $display
     *
     * @return void
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }
}
