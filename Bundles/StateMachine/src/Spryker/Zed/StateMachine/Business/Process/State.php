<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Process;

use Spryker\Zed\StateMachine\Business\Exception\StateMachineException;

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
     * @var \Spryker\Zed\StateMachine\Business\Process\ProcessInterface
     */
    protected $process;

    /**
     * @var string[]
     */
    protected $flags = [];

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    protected $outgoingTransitions = [];

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    protected $incomingTransitions = [];

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $incomingTransitions
     *
     * @return $this
     */
    public function setIncomingTransitions(array $incomingTransitions)
    {
        $this->incomingTransitions = $incomingTransitions;

        return $this;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
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
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $outgoingTransitions
     *
     * @return $this
     */
    public function setOutgoingTransitions(array $outgoingTransitions)
    {
        $this->outgoingTransitions = $outgoingTransitions;

        return $this;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
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
     * @param \Spryker\Zed\StateMachine\Business\Process\EventInterface $event
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
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
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface[]
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
     * @param string $eventName
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineException
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface
     */
    public function getEvent($eventName)
    {
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                $event = $transition->getEvent();
                if ($event->getName() === $eventName) {
                    return $event;
                }
            }
        }

        throw new StateMachineException(
            sprintf(
                'Event "%d" not found. Have you added this event to transition?',
                $eventName
            )
        );
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
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
     *
     * @return void
     */
    public function addIncomingTransition(TransitionInterface $transition)
    {
        $this->incomingTransitions[] = $transition;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     *
     * @return $this
     */
    public function setProcess($process)
    {
        $this->process = $process;

        return $this;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface
     */
    public function getProcess()
    {
        return $this->process;
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
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineException
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface
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

        throw new StateMachineException(
            sprintf(
                'There is no onEnter event for state "%s"',
                $this->getName()
            )
        );
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
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface[]
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
     * @return $this
     */
    public function addFlag($flag)
    {
        $this->flags[] = $flag;

        return $this;
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
     * @return string[]
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
     * @return $this
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }
}
