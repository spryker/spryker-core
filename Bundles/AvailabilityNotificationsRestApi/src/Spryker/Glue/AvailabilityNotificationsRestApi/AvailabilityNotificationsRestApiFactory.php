<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi;

use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Reader\AvailabilityNotificationReader;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Subscriber\AvailabilityNotificationSubscriber;
use Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Subscriber\AvailabilityNotificationSubscriberInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig getConfig()
 */
class AvailabilityNotificationsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Reader\AvailabilityNotificationReader
     */
    public function createAvailabilityNotificationReader(): AvailabilityNotificationReader
    {
        return new AvailabilityNotificationReader(
            $this->getAvailabilityNotificationClient()
        );
    }

    /**
     * @return \Spryker\Glue\AvailabilityNotificationsRestApi\Processor\Subscriber\AvailabilityNotificationSubscriberInterface
     */
    public function createAvailabilityNotificationSubscriber(): AvailabilityNotificationSubscriberInterface
    {
        return new AvailabilityNotificationSubscriber(
            $this->getAvailabilityNotificationClient()
        );
    }

    /**
     * @return \Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface
     *
     * @throws \Spryker\Glue\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getAvailabilityNotificationClient(): AvailabilityNotificationsRestApiToAvailabilityNotificationClientInterface
    {
        return $this->getProvidedDependency(AvailabilityNotificationsRestApiDependencyProvider::AVAILABILITY_NOTIFICATION_CLIENT);
    }
}
