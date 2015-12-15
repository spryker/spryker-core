<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Queue\Mock;

use Generated\Shared\Transfer\QueueMessageTransfer;
use Spryker\Zed\Queue\Dependency\Plugin\TaskPluginInterface;

class MockTaskPlugin implements TaskPluginInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $queueName;

    /**
     * @param string $name
     * @param string $queueName
     */
    public function __construct($name, $queueName)
    {
        $this->name = $name;
        $this->queueName = $queueName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return $this->queueName;
    }

    /**
     * @param QueueMessageTransfer $queueMessage
     *
     * @return void
     */
    public function run(QueueMessageTransfer $queueMessage)
    {
    }

}
