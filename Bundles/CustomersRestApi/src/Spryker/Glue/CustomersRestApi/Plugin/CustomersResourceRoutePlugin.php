<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Plugin;

use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin\AuthorizationStrategyAwareResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin\DefaultAuthorizationStrategyAwareResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class CustomersResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface, DefaultAuthorizationStrategyAwareResourceRoutePluginInterface, AuthorizationStrategyAwareResourceRoutePluginInterface
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
            ->addGet('get')
            ->addPost('post', false)
            ->addPatch('patch')
            ->addDelete('delete');

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
            ->setApiCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_UNAUTHORIZED);

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
     * @return array<\Generated\Shared\Transfer\RouteAuthorizationConfigTransfer>
     */
    public function getRouteAuthorizationConfigurations(): array
    {
        $routeAuthorizationConfigTransfer = (new RouteAuthorizationConfigTransfer())
            ->setApiCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_NOT_FOUND)
            ->setHttpStatusCode(Response::HTTP_NOT_FOUND)
            ->setApiMessage(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_NOT_FOUND);

        // The check for `method_exists` added for BC reason only.
        if (!method_exists($routeAuthorizationConfigTransfer, 'addStrategy')) {
            return [Request::METHOD_GET => $this->setStrategy($routeAuthorizationConfigTransfer)];
        }

        return [
            Request::METHOD_GET => $routeAuthorizationConfigTransfer->addStrategy(static::STRATEGY_NAME),
        ];
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
        return CustomersRestApiConfig::RESOURCE_CUSTOMERS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return 'customer-resource';
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
        return RestCustomersAttributesTransfer::class;
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
