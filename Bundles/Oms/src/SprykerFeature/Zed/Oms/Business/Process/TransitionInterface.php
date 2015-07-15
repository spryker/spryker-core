<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Process;

interface TransitionInterface
{

    /**
     * @param mixed $happy
     */
    public function setHappy($happy);

    /**
     * @return bool
     */
    public function isHappy();

    /**
     * @param string $condition
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
     */
    public function setSource($source);

    /**
     * @return StateInterface
     */
    public function getSource();

    /**
     * @param StateInterface $target
     */
    public function setTarget($target);

    /**
     * @return StateInterface
     */
    public function getTarget();

}
