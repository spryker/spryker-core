<?php

namespace SprykerFeature\Zed\Queue\Business\Worker;

interface TaskWorkerInterface
{

    /**
     * @param string $responseQueueName
     *
     * @return $this
     */
    public function setResponseQueueName($responseQueueName);

    /**
     * @param string $errorQueueName
     *
     * @return $this
     */
    public function setErrorQueueName($errorQueueName);

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
