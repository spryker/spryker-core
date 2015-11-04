<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Process;

class Event implements EventInterface
{

    protected $id;

    /**
     * @var TransitionInterface[]
     */
    protected $transitions;

    /**
     * @var bool
     */
    protected $onEnter;

    protected $command;

    protected $timeout;

    /**
     * @var bool
     */
    protected $manual;

    /**
     * @param mixed $manual
     *
     * @return void
     */
    public function setManual($manual)
    {
        $this->manual = $manual;
    }

    /**
     * @return mixed
     */
    public function isManual()
    {
        return $this->manual;
    }

    /**
     * @param mixed $command
     *
     * @return void
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return mixed
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
     * @param TransitionInterface $transition
     *
     * @return void
     */
    public function addTransition(TransitionInterface $transition)
    {
        $this->transitions[] = $transition;
    }

    /**
     * @param StateInterface $sourceState
     *
     * @return TransitionInterface[]
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
     * @return TransitionInterface[]
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    /**
     * @param mixed $timeout
     *
     * @return void
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return mixed
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
