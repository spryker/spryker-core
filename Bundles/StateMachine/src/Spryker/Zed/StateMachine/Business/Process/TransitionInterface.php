<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Process;

interface TransitionInterface
{
    /**
     * @param bool $happy
     *
     * @return void
     */
    public function setHappyCase($happy);

    /**
     * @return bool
     */
    public function isHappyCase();

    /**
     * @param string $condition
     *
     * @return void
     */
    public function setCondition($condition);

    /**
     * @return string
     */
    public function getCondition();

    /**
     * @return bool
     */
    public function hasCondition();

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\EventInterface $event
     *
     * @return void
     */
    public function setEvent(EventInterface $event);

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface
     */
    public function getEvent();

    /**
     * @return bool
     */
    public function hasEvent();

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $source
     *
     * @return void
     */
    public function setSourceState(StateInterface $source);

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    public function getSourceState();

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $target
     *
     * @return void
     */
    public function setTargetState(StateInterface $target);

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    public function getTargetState();
}
