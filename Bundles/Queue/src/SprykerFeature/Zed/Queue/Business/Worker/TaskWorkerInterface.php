<?php

namespace SprykerFeature\Zed\Queue\Business\Worker;

use SprykerFeature\Zed\Queue\Business\Model\QueueInterface;

interface TaskWorkerInterface
{
    /**
     * @param QueueInterface $responseQueue
     *
     * @return $this
     */
    public function setResponseQueue(QueueInterface $responseQueue);

    /**
     * @param QueueInterface $errorQueue
     *
     * @return $this
     */
    public function setErrorQueue(QueueInterface $errorQueue);

    /**
     * @param int $maxMessages
     *
     * @return $this
     */
    public function setMaxMessages($maxMessages);

    /**
     * @param ErrorHandlerInterface $errorHandler
     *
     * @return $this
     */
    public function setErrorHandler(ErrorHandlerInterface $errorHandler);

    /**
     * @param int $timeout
     * @param int $fetchSize
     */
    public function work($timeout = 10, $fetchSize = 10);
}
