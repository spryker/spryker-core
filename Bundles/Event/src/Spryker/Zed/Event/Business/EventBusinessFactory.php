<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\Event\Business;

use Spryker\Shared\Log\Config\LoggerConfigInterface;
use Spryker\Zed\Event\Business\Dispatcher\EventDispatcher;
use Spryker\Zed\Event\Business\Dispatcher\EventDispatcherInterface;
use Spryker\Zed\Event\Business\Dumper\EventListenerDumper;
use Spryker\Zed\Event\Business\Dumper\EventListenerDumperInterface;
use Spryker\Zed\Event\Business\Logger\EventLogger;
use Spryker\Zed\Event\Business\Logger\EventLoggerInterface;
use Spryker\Zed\Event\Business\Logger\LoggerConfig;
use Spryker\Zed\Event\Business\Queue\Consumer\EventQueueConsumer;
use Spryker\Zed\Event\Business\Queue\Consumer\EventQueueConsumerInterface;
use Spryker\Zed\Event\Business\Queue\Forwarder\MessageForwarder;
use Spryker\Zed\Event\Business\Queue\Forwarder\MessageForwarderInterface;
use Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducer;
use Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducerInterface;
use Spryker\Zed\Event\Business\Subscriber\SubscriberMerger;
use Spryker\Zed\Event\Business\Subscriber\SubscriberMergerInterface;
use Spryker\Zed\Event\Dependency\Client\EventToQueueInterface;
use Spryker\Zed\Event\Dependency\EventCollection;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\EventSubscriberCollectionInterface;
use Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface;
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
    public function createEventDispatcher(): EventDispatcherInterface
    {
        $eventListeners = $this->createSubscriberMerger()
            ->mergeSubscribersWith($this->getEventListeners());

        return new EventDispatcher(
            $eventListeners,
            $this->createEventQueueProducer(),
            $this->createEventLogger(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducerInterface
     */
    public function createEventQueueProducer(): EventQueueProducerInterface
    {
        return new EventQueueProducer(
            $this->getQueueClient(),
            $this->getUtilEncodingService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Event\Business\Queue\Consumer\EventQueueConsumerInterface
     */
    public function createEventQueueConsumer(): EventQueueConsumerInterface
    {
        return new EventQueueConsumer($this->createEventLogger(), $this->getUtilEncodingService(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Event\Business\Queue\Forwarder\MessageForwarderInterface
     */
    public function createMessageForwarder(): MessageForwarderInterface
    {
        return new MessageForwarder($this->getQueueClient());
    }

    /**
     * @return \Spryker\Zed\Event\Business\Subscriber\SubscriberMergerInterface
     */
    public function createSubscriberMerger(): SubscriberMergerInterface
    {
        return new SubscriberMerger($this->getEventSubscriberCollection());
    }

    /**
     * @return \Spryker\Shared\Log\Config\LoggerConfigInterface
     */
    public function createLoggerConfig(): LoggerConfigInterface
    {
        return new LoggerConfig($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getEventListeners(): EventCollectionInterface
    {
        return $this->getProvidedDependency(EventDependencyProvider::EVENT_LISTENERS);
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\EventSubscriberCollectionInterface
     */
    public function getEventSubscriberCollection(): EventSubscriberCollectionInterface
    {
        return $this->getProvidedDependency(EventDependencyProvider::EVENT_SUBSCRIBERS);
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface
     */
    public function getUtilEncodingService(): EventToUtilEncodingInterface
    {
        return $this->getProvidedDependency(EventDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\Client\EventToQueueInterface
     */
    public function getQueueClient(): EventToQueueInterface
    {
        return $this->getProvidedDependency(EventDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Spryker\Zed\Event\Business\Logger\EventLoggerInterface
     */
    public function createEventLogger(): EventLoggerInterface
    {
        return new EventLogger($this->createLoggerConfig(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Event\Business\Dumper\EventListenerDumperInterface
     */
    public function createEventListenerDumper(): EventListenerDumperInterface
    {
        return new EventListenerDumper($this->createSubscriberMerger(), $this->createEventCollection());
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function createEventCollection(): EventCollectionInterface
    {
        return new EventCollection();
    }
}
