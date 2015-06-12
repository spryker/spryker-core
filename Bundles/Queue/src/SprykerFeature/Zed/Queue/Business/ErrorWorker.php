<?php

namespace ProjectA\Queue;

use ProjectA\Queue\Api;
use ProjectA\Queue\PersistableInterface;
use ProjectA\Queue\QueueMessage;
use PhpAmqpLib\Message\AMQPMessage;
use ProjectA\Queue\Task\TaskInterface;

class ErrorWorker extends Worker
{

    /**
     * @var array
     */
    protected $registry = [];

    /**
     * @var PersistableInterface
     */
    protected $persistenceHandler;

    /**
     * @param Api $api
     * @param TaskInterface $taskName
     */
    public function __construct(Api $api, $taskName)
    {
        $this->api = $api;
        $this->taskName = $taskName;
    }

    /**
     * @param PersistableInterface $persistenceHandler
     */
    public function setPersistenceHandler(PersistableInterface $persistenceHandler)
    {
        $this->persistenceHandler = $persistenceHandler;
    }

    /**
     * @param QueueMessage $queueMessage
     * @return bool
     */
    protected function call(QueueMessage $queueMessage)
    {
        $error = $queueMessage->getError();
        if (isset($this->registry[$error])) {
            $this->registry[$error]['count']++;
        } else {
            $this->registry[$error] = [
                'count' => 1,
                'error' => $error,
                'content' => serialize($queueMessage->getDataObject())
            ];
        }

        $this->api->acknowledge($queueMessage);
        $this->processedMessages++;
        if (!is_null($this->persistenceHandler)) {
            $this->persistenceHandler->handleStoring($queueMessage);
        }

        return true;
    }
}
