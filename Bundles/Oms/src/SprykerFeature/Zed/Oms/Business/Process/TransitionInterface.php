<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Process;

interface TransitionInterface
{

    /**
     * @param mixed $happy
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
     * @param EventInterface $event
     *
     * @return void
     */
    public function setEvent($event);

    /**
     * @return EventInterface
     */
    public function getEvent();

    /**
     * @return bool
     */
    public function hasEvent();

    /**
     * @param StateInterface $source
     *
     * @return void
     */
    public function setSource($source);

    /**
     * @return StateInterface
     */
    public function getSource();

    /**
     * @param StateInterface $target
     *
     * @return void
     */
    public function setTarget($target);

    /**
     * @return StateInterface
     */
    public function getTarget();

}
