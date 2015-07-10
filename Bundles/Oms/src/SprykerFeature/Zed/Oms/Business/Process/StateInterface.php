<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Process;

interface StateInterface
{

    /**
     * @param TransitionInterface[] $incomingTransitions
     */
    public function setIncomingTransitions(array $incomingTransitions);

    /**
     * @return TransitionInterface[]
     */
    public function getIncomingTransitions();

    /**
     * @return bool
     */
    public function hasIncomingTransitions();

    /**
     * @param TransitionInterface[] $outgoingTransitions
     */
    public function setOutgoingTransitions(array $outgoingTransitions);

    /**
     * @return TransitionInterface[]
     */
    public function getOutgoingTransitions();

    /**
     * @return bool
     */
    public function hasOutgoingTransitions();

    /**
     * @param EventInterface $event
     *
     * @return TransitionInterface[]
     */
    public function getOutgoingTransitionsByEvent(EventInterface $event);

    /**
     * @return EventInterface[]
     */
    public function getEvents();

    /**
     * @param string $id
     *
     * @throws \Exception
     *
     * @return EventInterface
     */
    public function getEvent($id);

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasEvent($id);

    /**
     * @return bool
     */
    public function hasAnyEvent();

    /**
     * @param TransitionInterface $transition
     */
    public function addIncomingTransition(TransitionInterface $transition);

    /**
     * @param TransitionInterface $transition
     */
    public function addOutgoingTransition(TransitionInterface $transition);

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param ProcessInterface $process
     */
    public function setProcess($process);

    /**
     * @return ProcessInterface
     */
    public function getProcess();

    /**
     * @param bool $reserved
     */
    public function setReserved($reserved);

    /**
     * @return bool
     */
    public function isReserved();

    /**
     * @return bool
     */
    public function hasOnEnterEvent();

    /**
     * @throws \Exception
     *
     * @return EventInterface
     */
    public function getOnEnterEvent();

    /**
     * @return bool
     */
    public function hasTimeoutEvent();

    /**
     * @throws \Exception
     *
     * @return EventInterface[]
     */
    public function getTimeoutEvents();

    /**
     * @param string $flag
     */
    public function addFlag($flag);

    /**
     * @param string $flag
     *
     * @return bool
     */
    public function hasFlag($flag);

    /**
     * @return bool
     */
    public function hasFlags();

    /**
     * @return array
     */
    public function getFlags();

    /**
     * @return string
     */
    public function getDisplay();

    /**
     * @param string $display
     */
    public function setDisplay($display);

}
