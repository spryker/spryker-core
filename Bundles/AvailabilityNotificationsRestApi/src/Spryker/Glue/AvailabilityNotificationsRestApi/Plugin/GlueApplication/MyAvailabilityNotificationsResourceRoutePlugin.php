<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestAvailabilityNotificationRequestAttributesTransfer;
use Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\WishlistsRestApi\WishlistsRestApiFactory getFactory()
 */
class MyAvailabilityNotificationsResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        $resourceRouteCollection
            ->addGet('get', true);

        return $resourceRouteCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return AvailabilityNotificationsRestApiConfig::RESOURCE_MY_AVAILABILITY_NOTIFICATIONS;
    }

    /**
     * {@inheritDoc}
     *
     * @uses \Spryker\Glue\AvailabilityNotificationsRestApi\Controller\AvailabilityNotificationsResourceController
     *
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return 'my-availability-notifications-resource';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestAvailabilityNotificationRequestAttributesTransfer::class;
    }
}
