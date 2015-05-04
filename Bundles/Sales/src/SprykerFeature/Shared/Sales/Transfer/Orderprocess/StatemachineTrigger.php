<?php

namespace Generated\Shared\Transfer\SalesOrderprocessTransfer;

/**
 *
 */
class StatemachineTrigger extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $eventName = null;

    protected $orderId = null;

    protected $references = array(

    );

    protected $context = null;

    protected $observer = null;

    /**
     * @param string $eventName
     * @return $this
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
        $this->addModifiedProperty('eventName');
        return $this;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        $this->addModifiedProperty('orderId');
        return $this;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param array $references
     * @return $this
     */
    public function setReferences(array $references)
    {
        $this->references = $references;
        $this->addModifiedProperty('references');
        return $this;
    }

    /**
     * @return array
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @param mixed $reference
     * @return array
     */
    public function addReference($reference)
    {
        $this->references[] = $reference;
        return $this;
    }

    /**
     * @param \SprykerFeature_Zed_Library_StateMachine_Context $context
     * @return $this
     */
    public function setContext(\SprykerFeature_Zed_Library_StateMachine_Context $context)
    {
        $this->context = $context;
        $this->addModifiedProperty('context');
        return $this;
    }

    /**
     * @return \SprykerFeature_Zed_Library_StateMachine_Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param \SplObserver $observer
     * @return $this
     */
    public function setObserver(\SplObserver $observer)
    {
        $this->observer = $observer;
        $this->addModifiedProperty('observer');
        return $this;
    }

    /**
     * @return \SplObserver
     */
    public function getObserver()
    {
        return $this->observer;
    }


}
