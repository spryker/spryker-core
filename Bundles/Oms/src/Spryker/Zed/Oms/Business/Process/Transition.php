<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Process;

class Transition implements TransitionInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\Process\EventInterface
     */
    protected $event;

    /**
     * @var string
     */
    protected $condition;

    /**
     * @var bool
     */
    protected $happy;

    /**
     * @var \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    protected $source;

    /**
     * @var \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    protected $target;

    /**
     * @param bool $happy
     *
     * @return void
     */
    public function setHappy($happy)
    {
        $this->happy = $happy;
    }

    /**
     * @return bool
     */
    public function isHappy()
    {
        return $this->happy;
    }

    /**
     * @param string $condition
     *
     * @return void
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return bool
     */
    public function hasCondition()
    {
        return isset($this->condition);
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\EventInterface $event
     *
     * @return void
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return bool
     */
    public function hasEvent()
    {
        return isset($this->event);
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $source
     *
     * @return void
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $target
     *
     * @return void
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    public function getTarget()
    {
        return $this->target;
    }
}
