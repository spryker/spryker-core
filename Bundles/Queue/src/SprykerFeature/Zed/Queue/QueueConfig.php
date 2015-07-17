<?php

namespace SprykerFeature\Zed\Queue;

use Generated\Shared\Transfer\AmqpParameterTransfer;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\System\SystemConfig;

class QueueConfig extends AbstractBundleConfig
{

    /**
     * @return AmqpParameterTransfer
     */
    public function getAmqpParameter()
    {
        $amqpParameter = new AmqpParameterTransfer();

        $amqpParameter->setHost($this->get(SystemConfig::ZED_RABBITMQ_HOST));
        $amqpParameter->setVhost($this->get(SystemConfig::ZED_RABBITMQ_VHOST));
        $amqpParameter->setUser($this->get(SystemConfig::ZED_RABBITMQ_USERNAME));
        $amqpParameter->setPassword($this->get(SystemConfig::ZED_RABBITMQ_PASSWORD));
        $amqpParameter->setPort($this->get(SystemConfig::ZED_RABBITMQ_PORT));

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
