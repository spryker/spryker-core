<?php

namespace SprykerFeature\Zed\Queue\Business\Worker;

interface TaskWorkerInterface
{

    /**
     * @param string $responseQueueName
     *
     * @return self
     */
    public function setResponseQueueName($responseQueueName);

    /**
     * @param string $errorQueueName
     *
     * @return self
     */
    public function setErrorQueueName($errorQueueName);

    /**
     * @param int $maxMessages
     *
     * @return self
     */
    public function setMaxMessages($maxMessages);

    /**
     * @param ErrorHandlerInterface $errorHandler
     *
     * @return self
     */
    public function setErrorHandler(ErrorHandlerInterface $errorHandler);

    /**
     * @param int $timeout
     * @param int $fetchSize
     */
    public function work($timeout = 10, $fetchSize = 10);

}
