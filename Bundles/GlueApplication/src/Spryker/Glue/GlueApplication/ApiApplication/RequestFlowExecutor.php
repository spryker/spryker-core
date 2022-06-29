<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\ApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplication\Executor\ResourceExecutorInterface;
use Spryker\Glue\GlueApplication\Router\RouteMatcherInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

class RequestFlowExecutor implements RequestFlowExecutorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Executor\ResourceExecutorInterface
     */
    protected ResourceExecutorInterface $resourceExecutor;

    /**
     * @var \Spryker\Glue\GlueApplication\Router\RouteMatcherInterface
     */
    protected RouteMatcherInterface $routeMatcher;

    /**
     * @param \Spryker\Glue\GlueApplication\Executor\ResourceExecutorInterface $resourceExecutor
     * @param \Spryker\Glue\GlueApplication\Router\RouteMatcherInterface $routeMatcher
     */
    public function __construct(
        ResourceExecutorInterface $resourceExecutor,
        RouteMatcherInterface $routeMatcher
    ) {
        $this->resourceExecutor = $resourceExecutor;
        $this->routeMatcher = $routeMatcher;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $conventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function executeRequestFlow(
        GlueRequestTransfer $glueRequestTransfer,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $conventionPlugin = null
    ): GlueResponseTransfer {
        $glueRequestTransfer = $this->executeRequestBuilderPlugins(
            $glueRequestTransfer,
            $apiApplication,
            $conventionPlugin,
        );

        $glueRequestValidationTransfer = $this->executeRequestValidatorPlugins(
            $glueRequestTransfer,
            $apiApplication,
            $conventionPlugin,
        );
        if ($glueRequestValidationTransfer->getIsValid() === false) {
            return $this->sendValidationErrorResponse($glueRequestTransfer, $glueRequestValidationTransfer, $apiApplication, $conventionPlugin);
        }

        $resource = $this->routeMatcher->route($glueRequestTransfer);
        if ($resource instanceof MissingResourceInterface) {
            return $this->sendMissingResourceResponse($glueRequestTransfer, $resource, $apiApplication, $conventionPlugin);
        }

        $glueRequestValidationTransfer = $this->executeRequestAfterRoutingValidatorPlugins(
            $glueRequestTransfer,
            $resource,
            $apiApplication,
            $conventionPlugin,
        );
        if (!$glueRequestValidationTransfer->getIsValid()) {
            return $this->sendValidationErrorResponse($glueRequestTransfer, $glueRequestValidationTransfer, $apiApplication, $conventionPlugin);
        }

        $glueResponseTransfer = $this->resourceExecutor->executeResource($resource, $glueRequestTransfer);

        $glueResponseTransfer = $this->executeResponseFormatterPlugins(
            $glueResponseTransfer,
            $glueRequestTransfer,
            $apiApplication,
            $conventionPlugin,
        );

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConvention
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function sendValidationErrorResponse(
        GlueRequestTransfer $glueRequestTransfer,
        GlueRequestValidationTransfer $glueRequestValidationTransfer,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConvention = null
    ): GlueResponseTransfer {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus($glueRequestValidationTransfer->getStatus())
            ->setErrors($glueRequestValidationTransfer->getErrors());

        return $this->executeResponseFormatterPlugins(
            $glueResponseTransfer,
            $glueRequestTransfer,
            $apiApplication,
            $apiConvention,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface $missingResource
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface $apiConvention
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function sendMissingResourceResponse(
        GlueRequestTransfer $glueRequestTransfer,
        MissingResourceInterface $missingResource,
        RequestFlowAwareApiApplication $apiApplication,
        ConventionPluginInterface $apiConvention
    ): GlueResponseTransfer {
        $glueResponseTransfer = $this->resourceExecutor->executeResource($missingResource, $glueRequestTransfer);

        return $this->executeResponseFormatterPlugins(
            $glueResponseTransfer,
            $glueRequestTransfer,
            $apiApplication,
            $apiConvention,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function executeRequestBuilderPlugins(
        GlueRequestTransfer $glueRequestTransfer,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): GlueRequestTransfer {
        $requestBuilderPlugins = [];
        if ($apiConventionPlugin) {
            $requestBuilderPlugins = $apiConventionPlugin->provideRequestBuilderPlugins();
        }

        $requestBuilderPlugins = array_merge($requestBuilderPlugins, $apiApplication->provideRequestBuilderPlugins());

        foreach ($requestBuilderPlugins as $requestBuilderPlugin) {
            $glueRequestTransfer = $requestBuilderPlugin->build($glueRequestTransfer);
        }

        return $glueRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function executeRequestValidatorPlugins(
        GlueRequestTransfer $glueRequestTransfer,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): GlueRequestValidationTransfer {
        $requestValidatorPlugins = [];
        if ($apiConventionPlugin) {
            $requestValidatorPlugins = $apiConventionPlugin->provideRequestValidatorPlugins();
        }

        $requestValidatorPlugins = array_merge($requestValidatorPlugins, $apiApplication->provideRequestValidatorPlugins());

        foreach ($requestValidatorPlugins as $requestValidatorPlugin) {
            $glueRequestValidationTransfer = $requestValidatorPlugin->validate($glueRequestTransfer);

            if ($glueRequestValidationTransfer->getIsValid()) {
                continue;
            }

            return $glueRequestValidationTransfer;
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    protected function executeRequestAfterRoutingValidatorPlugins(
        GlueRequestTransfer $glueRequestTransfer,
        ResourceInterface $resource,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): GlueRequestValidationTransfer {
        $requestAfterRoutingValidatorPlugins = [];
        if ($apiConventionPlugin) {
            $requestAfterRoutingValidatorPlugins = $apiConventionPlugin->provideRequestAfterRoutingValidatorPlugins();
        }

        $requestAfterRoutingValidatorPlugins = array_merge($requestAfterRoutingValidatorPlugins, $apiApplication->provideRequestAfterRoutingValidatorPlugins());

        foreach ($requestAfterRoutingValidatorPlugins as $requestAfterRoutingValidatorPlugin) {
            $glueRequestValidationTransfer = $requestAfterRoutingValidatorPlugin->validate($glueRequestTransfer, $resource);

            if ($glueRequestValidationTransfer->getIsValid()) {
                continue;
            }

            return $glueRequestValidationTransfer;
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConventionPlugin
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function executeResponseFormatterPlugins(
        GlueResponseTransfer $glueResponseTransfer,
        GlueRequestTransfer $glueRequestTransfer,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConventionPlugin
    ): GlueResponseTransfer {
        $responseFormatterPlugins = [];
        if ($apiConventionPlugin) {
            $responseFormatterPlugins = $apiConventionPlugin->provideResponseFormatterPlugins();
        }

        $responseFormatterPlugins = array_merge($responseFormatterPlugins, $apiApplication->provideResponseFormatterPlugins());

        foreach ($responseFormatterPlugins as $responseFormatterPlugin) {
            $glueResponseTransfer = $responseFormatterPlugin->format($glueResponseTransfer, $glueRequestTransfer);
        }

        return $glueResponseTransfer;
    }
}
