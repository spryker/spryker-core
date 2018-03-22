<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Process;

class Event implements EventInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    protected $transitions = [];

    /**
     * @var bool
     */
    protected $onEnter = false;

    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $timeout;

    /**
     * @var bool
     */
    protected $manual = false;

    /**
     * @param bool $manual
     *
     * @return void
     */
    public function setManual($manual)
    {
        $this->manual = $manual;
    }

    /**
     * @return bool
     */
    public function isManual()
    {
        return $this->manual;
    }

    /**
     * @param string $command
     *
     * @return void
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return bool
     */
    public function hasCommand()
    {
        return isset($this->command);
    }

    /**
     * @param bool $onEnter
     *
     * @return void
     */
    public function setOnEnter($onEnter)
    {
        $this->onEnter = $onEnter;
    }

    /**
     * @return bool
     */
    public function isOnEnter()
    {
        return $this->onEnter;
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function setName($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->id;
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
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $sourceState
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getTransitionsBySource(StateInterface $sourceState)
    {
        $transitions = [];

        foreach ($this->transitions as $transition) {
            if ($transition->getSourceState()->getName() !== $sourceState->getName()) {
                continue;
            }
            $transitions[] = $transition;
        }

        return $transitions;
    }

    /**
     * @return string
     */
    public function getEventTypeLabel()
    {
        if ($this->isOnEnter()) {
            return ' (on enter)';
        }

        if ($this->isManual()) {
            return ' (manual)';
        }

        if ($this->hasTimeout()) {
            return ' (timeout)';
        }

        return '';
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[]
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    /**
     * @param string $timeout
     *
     * @return void
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return string
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @return bool
     */
    public function hasTimeout()
    {
        return isset($this->timeout);
    }
}
