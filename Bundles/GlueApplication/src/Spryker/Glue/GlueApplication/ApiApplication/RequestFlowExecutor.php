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
use Spryker\Glue\GlueApplication\Builder\RequestBuilderInterface;
use Spryker\Glue\GlueApplication\Executor\ResourceExecutorInterface;
use Spryker\Glue\GlueApplication\Formatter\ResponseFormatterInterface;
use Spryker\Glue\GlueApplication\Router\RouteMatcherInterface;
use Spryker\Glue\GlueApplication\Validator\RequestValidatorInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface;

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
     * @var \Spryker\Glue\GlueApplication\Builder\RequestBuilderInterface
     */
    protected RequestBuilderInterface $requestBuilder;

    /**
     * @var \Spryker\Glue\GlueApplication\Validator\RequestValidatorInterface
     */
    protected RequestValidatorInterface $requestValidator;

    /**
     * @var \Spryker\Glue\GlueApplication\Formatter\ResponseFormatterInterface
     */
    protected ResponseFormatterInterface $responseFormatter;

    /**
     * @param \Spryker\Glue\GlueApplication\Executor\ResourceExecutorInterface $resourceExecutor
     * @param \Spryker\Glue\GlueApplication\Router\RouteMatcherInterface $routeMatcher
     * @param \Spryker\Glue\GlueApplication\Builder\RequestBuilderInterface $requestBuilder
     * @param \Spryker\Glue\GlueApplication\Validator\RequestValidatorInterface $requestValidator
     * @param \Spryker\Glue\GlueApplication\Formatter\ResponseFormatterInterface $responseFormatter
     */
    public function __construct(
        ResourceExecutorInterface $resourceExecutor,
        RouteMatcherInterface $routeMatcher,
        RequestBuilderInterface $requestBuilder,
        RequestValidatorInterface $requestValidator,
        ResponseFormatterInterface $responseFormatter
    ) {
        $this->resourceExecutor = $resourceExecutor;
        $this->routeMatcher = $routeMatcher;
        $this->requestBuilder = $requestBuilder;
        $this->requestValidator = $requestValidator;
        $this->responseFormatter = $responseFormatter;
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
        $glueRequestTransfer = $this->requestBuilder->build(
            $glueRequestTransfer,
            $apiApplication,
            $conventionPlugin,
        );

        $glueRequestValidationTransfer = $this->requestValidator->validate(
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

        $glueRequestValidationTransfer = $this->requestValidator->validateAfterRouting(
            $glueRequestTransfer,
            $resource,
            $apiApplication,
            $conventionPlugin,
        );

        if (!$glueRequestValidationTransfer->getIsValid()) {
            return $this->sendValidationErrorResponse($glueRequestTransfer, $glueRequestValidationTransfer, $apiApplication, $conventionPlugin);
        }

        $glueResponseTransfer = $this->resourceExecutor->executeResource($resource, $glueRequestTransfer);

        $glueResponseTransfer = $this->responseFormatter->format(
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

        return $this->responseFormatter->format(
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
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface|null $apiConvention
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function sendMissingResourceResponse(
        GlueRequestTransfer $glueRequestTransfer,
        MissingResourceInterface $missingResource,
        RequestFlowAwareApiApplication $apiApplication,
        ?ConventionPluginInterface $apiConvention
    ): GlueResponseTransfer {
        $glueResponseTransfer = $this->resourceExecutor->executeResource($missingResource, $glueRequestTransfer);

        return $this->responseFormatter->format(
            $glueResponseTransfer,
            $glueRequestTransfer,
            $apiApplication,
            $apiConvention,
        );
    }
}
