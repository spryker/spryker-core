<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi;

use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig getConfig()
 */
class AvailabilityNotificationsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const AVAILABILITY_NOTIFICATION_CLIENT = "AVAILABILITY_NOTIFICATION_CLIENT";

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addAvailabilityNotificationClient($container);

        return $container;
    }

    protected function addAvailabilityNotificationClient(Container $container): Container
    {
        $container->set(static::AVAILABILITY_NOTIFICATION_CLIENT, function(Container $container) {
            return new AvailabilityNotificationsRestApiToAvailabilityNotificationClientBridge(
                $container->getLocator()->availabilityNotification()->client()
            );
        });

        return $container;
    }
}
