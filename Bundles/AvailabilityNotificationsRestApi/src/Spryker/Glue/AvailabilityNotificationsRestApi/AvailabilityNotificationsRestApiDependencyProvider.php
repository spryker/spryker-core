<?php

namespace Spryker\Glue\AvailabilityNotificationsRestApi;

use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToAvailabilityNotificationClientBridge;
use Spryker\Glue\AvailabilityNotificationsRestApi\Dependency\Client\AvailabilityNotificationsRestApiToStoreClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig getConfig()
 */
class AvailabilityNotificationsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const AVAILABILITY_NOTIFICATION_CLIENT = "AVAILABILITY_NOTIFICATION_CLIENT";
    public const STORE_CLIENT = "STORE_CLIENT";

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     *
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addAvailabilityNotificationClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     *
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     */
    protected function addAvailabilityNotificationClient(Container $container): Container
    {
        $container->set(static::AVAILABILITY_NOTIFICATION_CLIENT, function(Container $container) {
            return new AvailabilityNotificationsRestApiToAvailabilityNotificationClientBridge(
                $container->getLocator()->availabilityNotification()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     *
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::STORE_CLIENT, function(Container $container) {
            return new AvailabilityNotificationsRestApiToStoreClientBridge(
                $container->getLocator()->store()->client()
            );
        });

        return $container;
    }
}
