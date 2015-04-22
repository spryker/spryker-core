<?php

namespace SprykerFeature\Zed\Oms\Business\Model\Process;

/**
 * Interface TransitionInterface
 * @package SprykerFeature\Zed\Oms\Business\Model\Process
 */
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
     * @param StatusInterface $source
     */
    public function setSource($source);

    /**
     * @return StatusInterface
     */
    public function getSource();

    /**
     * @param StatusInterface $target
     */
    public function setTarget($target);

    /**
     * @return StatusInterface
     */
    public function getTarget();
}