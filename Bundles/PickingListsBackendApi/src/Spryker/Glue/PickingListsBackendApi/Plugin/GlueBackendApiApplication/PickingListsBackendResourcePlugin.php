<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\ApiPickingListsRequestAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\AbstractResourcePlugin;
use Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin\AuthorizationStrategyAwareResourceRoutePluginInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\PickingListsBackendApi\Controller\PickingListResourceController;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PickingListsBackendResourcePlugin extends AbstractResourcePlugin implements JsonApiResourceInterface, AuthorizationStrategyAwareResourceRoutePluginInterface
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
        return PickingListResourceController::class;
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
            ->setPatch(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('patchAction')
                    ->setAttributes(ApiPickingListsRequestAttributesTransfer::class),
            )
            ->setGetCollection(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getCollectionAction'),
            )
            ->setGet(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getAction'),
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
            Request::METHOD_GET => $routeAuthorizationConfigTransfer->addStrategy(static::STRATEGY_NAME),
        ];
    }
}
