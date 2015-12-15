<?php

namespace Spryker\Zed\Queue;

use Generated\Shared\Transfer\AmqpParameterTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Shared\Application\ApplicationConstants;

class QueueConfig extends AbstractBundleConfig
{

    /**
     * @return AmqpParameterTransfer
     */
    public function getAmqpParameter()
    {
        $amqpParameter = new AmqpParameterTransfer();

        $amqpParameter->setHost($this->get(ApplicationConstants::ZED_RABBITMQ_HOST));
        $amqpParameter->setVhost($this->get(ApplicationConstants::ZED_RABBITMQ_VHOST));
        $amqpParameter->setUser($this->get(ApplicationConstants::ZED_RABBITMQ_USERNAME));
        $amqpParameter->setPassword($this->get(ApplicationConstants::ZED_RABBITMQ_PASSWORD));
        $amqpParameter->setPort($this->get(ApplicationConstants::ZED_RABBITMQ_PORT));

        return $amqpParameter;
    }

    /**
     * @return string
     */
    public function getErrorChannelName()
    {
        return 'error';
    }

    /**
     * @return int
     */
    public function getMaxWorkerMessageCount()
    {
        return 1000;
    }

}
