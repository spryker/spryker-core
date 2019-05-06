<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Process;

interface TransitionInterface
{
    /**
     * @param bool $happy
     *
     * @return void
     */
    public function setHappy($happy);

    /**
     * @return bool
     */
    public function isHappy();

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
     * @param \Spryker\Zed\Oms\Business\Process\EventInterface $event
     *
     * @return void
     */
    public function setEvent($event);

    /**
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface
     */
    public function getEvent();

    /**
     * @return bool
     */
    public function hasEvent();

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $source
     *
     * @return void
     */
    public function setSource($source);

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    public function getSource();

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $target
     *
     * @return void
     */
    public function setTarget($target);

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    public function getTarget();
}
