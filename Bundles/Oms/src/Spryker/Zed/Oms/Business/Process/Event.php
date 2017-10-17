<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Process;

class Event implements EventInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
     */
    protected $transitions;

    /**
     * @var bool
     */
    protected $onEnter;

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
    protected $manual;

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
     * @param mixed $id
     *
     * @return void
     */
    public function setName($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->id;
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
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $sourceState
     *
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
     */
    public function getTransitionsBySource(StateInterface $sourceState)
    {
        $transitions = [];

        foreach ($this->transitions as $transition) {
            if ($transition->getSource()->getName() === $sourceState->getName()) {
                $transitions[] = $transition;
            }
        }

        return $transitions;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
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
