<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend\AbstractResourcePlugin;
use Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin\AuthorizationStrategyAwareResourceRoutePluginInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\PickingListsBackendApi\Controller\PickingListStartPickingResourceController;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PickingListStartPickingBackendResourcePlugin extends AbstractResourcePlugin implements JsonApiResourceInterface, AuthorizationStrategyAwareResourceRoutePluginInterface
{
    /**
     * @uses \Spryker\Zed\OauthWarehouse\Communication\Plugin\Authorization\WarehouseTokenAuthorizationStrategyPlugin::STRATEGY_NAME
     *
     * @var string
     */
    protected const STRATEGY_NAME = 'WarehouseTokenAuthorizationStrategy';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getType(): string
    {
        return PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_START_PICKING;
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
        return PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS;
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
        return PickingListStartPickingResourceController::class;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer
    {
        return (new GlueResourceMethodCollectionTransfer())
            ->setPost(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('postAction'),
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string, \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer>
     */
    public function getRouteAuthorizationConfigurations(): array
    {
        $routeAuthorizationConfigTransfer = (new RouteAuthorizationConfigTransfer())
            ->setApiCode(PickingListsBackendApiConfig::RESPONSE_CODE_AUTHORIZATION_FAILED)
            ->setHttpStatusCode(Response::HTTP_FORBIDDEN)
            ->setApiMessage(PickingListsBackendApiConfig::RESPONSE_DETAILS_AUTHORIZATION_FAILED);

        return [
            Request::METHOD_POST => $routeAuthorizationConfigTransfer->addStrategy(static::STRATEGY_NAME),
        ];
    }
}
