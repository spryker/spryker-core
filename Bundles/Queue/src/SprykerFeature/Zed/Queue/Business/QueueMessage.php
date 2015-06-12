<?php

namespace ProjectA\Queue;

use PhpAmqpLib\Message\AMQPMessage;

class QueueMessage
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var DataObject
     */
    protected $dataObject;

    /**
     * @var AMQPMessage
     */
    protected $message;

    /**
     * @param DataObject $dataObject
     * @param AMQPMessage $message
     */
    public function __construct(DataObject $dataObject = null, AMQPMessage $message = null)
    {
        if (!is_null($dataObject)) {
            $this->id = $dataObject->getId();
            $this->dataObject = $dataObject;
        }
        if (!is_null($message)) {
            $this->message = $message;
        }
    }

    /**
     * @param string $error
     * @return QueueMessage
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $id
     * @return QueueMessage
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $object
     * @return QueueMessage
     */
    public function setDataObject($object)
    {
        $this->dataObject = $object;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataObject()
    {
        return $this->dataObject;
    }

    /**
     * @param AMQPMessage $message
     * @return QueueMessage
     */
    public function setMessage(AMQPMessage $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return AMQPMessage
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        // return everything but the AMQPMessage, as the AMQPMessage is not serializable and not needed
        return ['id', 'error', 'dataObject'];
    }
}
