<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AvailabilityNotificationsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestAvailabilityNotificationsAttributesTransfer;
use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiConfig;
use Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin\DefaultAuthorizationStrategyAwareResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\AvailabilityNotificationsRestApi\AvailabilityNotificationsRestApiFactory getFactory()
 */
class CustomerAvailabilityNotificationsResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface, ResourceWithParentPluginInterface, DefaultAuthorizationStrategyAwareResourceRoutePluginInterface
{
    /**
     * @uses \Spryker\Client\Customer\Plugin\Authorization\CustomerReferenceMatchingEntityIdAuthorizationStrategyPlugin::STRATEGY_NAME
     *
     * @var string
     */
    protected const STRATEGY_NAME = 'CustomerReferenceMatchingEntityId';

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
     * @return \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer
     */
    public function getRouteAuthorizationDefaultConfiguration(): RouteAuthorizationConfigTransfer
    {
        $routeAuthorizationConfigTransfer = (new RouteAuthorizationConfigTransfer())
            ->setApiCode(AvailabilityNotificationsRestApiConfig::RESPONSE_CODE_CUSTOMER_UNAUTHORIZED);

        // The check for `method_exists` added for BC reason only.
        if (!method_exists($routeAuthorizationConfigTransfer, 'addStrategy')) {
            return $this->setStrategy($routeAuthorizationConfigTransfer);
        }

        return $routeAuthorizationConfigTransfer->addStrategy(static::STRATEGY_NAME);
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
        return AvailabilityNotificationsRestApiConfig::RESOURCE_AVAILABILITY_NOTIFICATIONS;
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
        return 'customer-availability-notifications-resource';
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
        return RestAvailabilityNotificationsAttributesTransfer::class;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getParentResourceType(): string
    {
        return AvailabilityNotificationsRestApiConfig::RESOURCE_CUSTOMERS;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer $routeAuthorizationConfigTransfer
     *
     * @return \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer
     */
    protected function setStrategy(RouteAuthorizationConfigTransfer $routeAuthorizationConfigTransfer): RouteAuthorizationConfigTransfer
    {
        return $routeAuthorizationConfigTransfer->setStrategy(static::STRATEGY_NAME);
    }
}
