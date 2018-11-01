<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Process;

interface EventInterface
{
    /**
     * @param bool $manual
     *
     * @return void
     */
    public function setManual($manual);

    /**
     * @return bool
     */
    public function isManual();

    /**
     * @param string $command
     *
     * @return void
     */
    public function setCommand($command);

    /**
     * @return string
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
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     *
     * @return void
     */
    public function addTransition(TransitionInterface $transition);

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $sourceState
     *
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
     */
    public function getTransitionsBySource(StateInterface $sourceState);

    /**
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface[]
     */
    public function getTransitions();

    /**
     * Specification:
     * - Any valid for \DateInterval::createFromDateString() input ("1 hour", "1 week" etc)
     *
     * @param string $timeout
     *
     * @return void
     */
    public function setTimeout($timeout);

    /**
     * @return string
     */
    public function getTimeout();

    /**
     * @return bool
     */
    public function hasTimeout();
}
