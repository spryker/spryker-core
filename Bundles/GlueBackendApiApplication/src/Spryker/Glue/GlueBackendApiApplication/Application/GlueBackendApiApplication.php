<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Application;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig;
use Spryker\Glue\GlueBackendApiApplication\Resource\MissingResource;
use Spryker\Glue\GlueBackendApiApplication\Resource\PreFlightResource;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory getFactory()
 */
class GlueBackendApiApplication extends RequestFlowAwareApiApplication
{
    /**
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * {@inheritDoc}
     * - Builds request for the Backend API Application.
     * - Expands `GlueRequestTransfer` with GlueBackendApiApplication name.
     * - Runs a stack of {@link \Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $glueRequestTransfer->setApplication(static::GLUE_BACKEND_API_APPLICATION);

        foreach ($this->getFactory()->getRequestBuilderPlugins() as $builderRequestPlugin) {
            $glueRequestTransfer = $builderRequestPlugin->build($glueRequestTransfer);
        }

        return $glueRequestTransfer;
    }

    /**
     * {@inheritDoc}
     * - Executes a stack of {@link \Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface} plugins.
     * - Plugins are executed until the first one fails, then the failed validation response is returned and subsequent validators are not executed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validateRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        foreach ($this->getFactory()->getRequestValidatorPlugins() as $validateRequestPlugin) {
            $glueRequestValidationTransfer = $validateRequestPlugin->validate($glueRequestTransfer);

            if ($glueRequestValidationTransfer->getIsValid() === false) {
                break;
            }
        }

        return $glueRequestValidationTransfer ?? (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * {@inheritDoc}
     * - Executes a stack of {@link \Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface} plugins.
     * - Plugins are executed until the first one fails, then the failed validation response is returned and subsequent validators are not executed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validateRequestAfterRouting(
        GlueRequestTransfer $glueRequestTransfer,
        ResourceInterface $resource
    ): GlueRequestValidationTransfer {
        foreach ($this->getFactory()->getRequestAfterRoutingValidatorPlugins() as $validateRequestAfterRoutingPlugin) {
            $glueRequestValidationTransfer = $validateRequestAfterRoutingPlugin->validateRequest($glueRequestTransfer, $resource);

            if ($glueRequestValidationTransfer->getIsValid() === false) {
                break;
            }
        }

        return $glueRequestValidationTransfer ?? (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * {@inheritDoc}
     * - Runs a stack of {@link \Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function formatResponse(GlueResponseTransfer $glueResponseTransfer, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        foreach ($this->getFactory()->getResponseFormatterPlugins() as $formatResponsePlugin) {
            $glueResponseTransfer = $formatResponsePlugin->format($glueResponseTransfer, $glueRequestTransfer);
        }

        return $glueResponseTransfer;
    }

    /**
     * {@inheritDoc}
     * - Runs a stack of {@link \Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouteMatcherPluginInterface}.
     * - Executes until the first plugin returns a valid instance of {@link \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface}.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    public function route(GlueRequestTransfer $glueRequestTransfer): ResourceInterface
    {
        $routeMatcherPlugins = $this->getFactory()->getRouteMatcherPlugins();
        foreach ($routeMatcherPlugins as $routeMatcherPlugin) {
            $resourcePlugin = $routeMatcherPlugin->route($glueRequestTransfer, $this->getFactory()->getResourcePlugins());

            if (!$resourcePlugin instanceof MissingResourceInterface) {
                if (
                    $glueRequestTransfer->getMethod() === Request::METHOD_OPTIONS &&
                    !$resourcePlugin->getDeclaredMethods()->getOptions()
                ) {
                    return new PreFlightResource($resourcePlugin);
                }

                return $resourcePlugin;
            }
        }

        return new MissingResource(
            GlueBackendApiApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
            GlueBackendApiApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
        );
    }
}
