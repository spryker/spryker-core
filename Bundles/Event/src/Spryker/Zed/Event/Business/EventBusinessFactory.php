<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business;

use Spryker\Zed\Event\Business\Dispatcher\EventDispatcher;
use Spryker\Zed\Event\Business\Logger\EventLogger;
use Spryker\Zed\Event\Business\Logger\LoggerConfig;
use Spryker\Zed\Event\Business\Queue\Consumer\EventQueueConsumer;
use Spryker\Zed\Event\Business\Queue\Forwarder\MessageForwarder;
use Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducer;
use Spryker\Zed\Event\Business\Subscriber\SubscriberMerger;
use Spryker\Zed\Event\EventDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Event\EventConfig getConfig()
 */
class EventBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Event\Business\Dispatcher\EventDispatcherInterface
     */
    public function createEventDispatcher()
    {
        $eventListeners = $this->createSubscriberMerger()
            ->mergeSubscribersWith($this->getEventListeners());

        return new EventDispatcher(
            $eventListeners,
            $this->createEventQueueProducer(),
            $this->createEventLogger(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducerInterface
     */
    protected function createEventQueueProducer()
    {
        return new EventQueueProducer($this->getQueueClient(), $this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\Event\Business\Queue\Consumer\EventQueueConsumerInterface
     */
    public function createEventQueueConsumer()
    {
        return new EventQueueConsumer($this->createEventLogger(), $this->getUtilEncodingService(), $this->getConfig()->getMaxRetryAmount());
    }

    /**
     * @return \Spryker\Zed\Event\Business\Queue\Forwarder\MessageForwarderInterface
     */
    public function createMessageForwarder()
    {
        return new MessageForwarder($this->getQueueClient(), $this->getQueueQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Event\Business\Subscriber\SubscriberMergerInterface
     */
    protected function createSubscriberMerger()
    {
        return new SubscriberMerger($this->getEventSubscriberCollection());
    }

    /**
     * @return \Spryker\Shared\Log\Config\LoggerConfigInterface
     */
    protected function createLoggerConfig()
    {
        return new LoggerConfig($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function getEventListeners()
    {
        return $this->getProvidedDependency(EventDependencyProvider::EVENT_LISTENERS);
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\EventSubscriberCollectionInterface
     */
    protected function getEventSubscriberCollection()
    {
        return $this->getProvidedDependency(EventDependencyProvider::EVENT_SUBSCRIBERS);
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(EventDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\Client\EventToQueueInterface
     */
    protected function getQueueClient()
    {
        return $this->getProvidedDependency(EventDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\QueryContainer\EventToQueueQueryContainerInterface
     */
    protected function getQueueQueryContainer()
    {
        return $this->getProvidedDependency(EventDependencyProvider::QUERY_CONTAINER_QUEUE);
    }

    /**
     * @return \Spryker\Zed\Event\Business\Logger\EventLoggerInterface
     */
    protected function createEventLogger()
    {
        return new EventLogger($this->createLoggerConfig(), $this->getConfig());
    }
}
