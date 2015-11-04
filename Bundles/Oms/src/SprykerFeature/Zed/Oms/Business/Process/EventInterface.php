<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Process;

interface EventInterface
{

    /**
     * @param mixed $manual
     *
     * @return void
     */
    public function setManual($manual);

    /**
     * @return mixed
     */
    public function isManual();

    /**
     * @param mixed $command
     *
     * @return void
     */
    public function setCommand($command);

    /**
     * @return mixed
     */
    public function getCommand();

    /**
     * @return bool
     */
    public function hasCommand();

    /**
     * @param bool $onEnter
     *
     * @return void
     */
    public function setOnEnter($onEnter);

    /**
     * @return bool
     */
    public function isOnEnter();

    /**
     * @param mixed $id
     *
     * @return void
     */
    public function setName($id);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param TransitionInterface $transition
     *
     * @return void
     */
    public function addTransition(TransitionInterface $transition);

    /**
     * @param StateInterface $sourceState
     *
     * @return TransitionInterface[]
     */
    public function getTransitionsBySource(StateInterface $sourceState);

    /**
     * @return TransitionInterface[]
     */
    public function getTransitions();

    /**
     * @param mixed $timeout
     *
     * @return void
     */
    public function setTimeout($timeout);

    /**
     * @return mixed
     */
    public function getTimeout();

    /**
     * @return bool
     */
    public function hasTimeout();

}
