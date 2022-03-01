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
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface;

class RequestFlowExecutor implements RequestFlowExecutorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Executor\ResourceExecutorInterface
     */
    protected ResourceExecutorInterface $resourceExecutor;

    /**
     * @param \Spryker\Glue\GlueApplication\Executor\ResourceExecutorInterface $resourceExecutor
     */
    public function __construct(ResourceExecutorInterface $resourceExecutor)
    {
        $this->resourceExecutor = $resourceExecutor;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface $apiConventionPlugin
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function executeRequestFlow(
        RequestFlowAwareApiApplication $apiApplication,
        ApiConventionPluginInterface $apiConventionPlugin,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $glueRequestTransfer = $apiConventionPlugin->buildRequest($glueRequestTransfer);
        $glueRequestTransfer = $apiApplication->buildRequest($glueRequestTransfer);

        $glueRequestValidationTransfer = $apiConventionPlugin->validateRequest($glueRequestTransfer);
        if ($glueRequestValidationTransfer->getIsValid() === false) {
            return $this->sendValidationErrorResponse($glueRequestTransfer, $glueRequestValidationTransfer, $apiApplication, $apiConventionPlugin);
        }
        $glueRequestValidationTransfer = $apiApplication->validateRequest($glueRequestTransfer);
        if ($glueRequestValidationTransfer->getIsValid() === false) {
            return $this->sendValidationErrorResponse($glueRequestTransfer, $glueRequestValidationTransfer, $apiApplication, $apiConventionPlugin);
        }

        $resource = $apiApplication->route($glueRequestTransfer);
        if ($resource instanceof MissingResourceInterface) {
            return $this->sendMissingResourceResponse($glueRequestTransfer, $resource, $apiApplication, $apiConventionPlugin);
        }

        $glueRequestValidationTransfer = $apiConventionPlugin->validateRequestAfterRouting($glueRequestTransfer, $resource);
        if (!$glueRequestValidationTransfer->getIsValid()) {
            return $this->sendValidationErrorResponse($glueRequestTransfer, $glueRequestValidationTransfer, $apiApplication, $apiConventionPlugin);
        }
        $glueRequestValidationTransfer = $apiApplication->validateRequestAfterRouting($glueRequestTransfer, $resource);
        if (!$glueRequestValidationTransfer->getIsValid()) {
            return $this->sendValidationErrorResponse($glueRequestTransfer, $glueRequestValidationTransfer, $apiApplication, $apiConventionPlugin);
        }

        $glueResponseTransfer = $this->resourceExecutor->executeResource($resource, $glueRequestTransfer);

        $glueResponseTransfer = $apiConventionPlugin->formatResponse($glueResponseTransfer, $glueRequestTransfer);
        $glueResponseTransfer = $apiApplication->formatResponse($glueResponseTransfer, $glueRequestTransfer);

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface $apiConvention
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function sendValidationErrorResponse(
        GlueRequestTransfer $glueRequestTransfer,
        GlueRequestValidationTransfer $glueRequestValidationTransfer,
        RequestFlowAwareApiApplication $apiApplication,
        ApiConventionPluginInterface $apiConvention
    ): GlueResponseTransfer {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus($glueRequestValidationTransfer->getStatus())
            ->setErrors($glueRequestValidationTransfer->getErrors());

        $glueResponseTransfer = $apiConvention->formatResponse($glueResponseTransfer, $glueRequestTransfer);

        return $apiApplication->formatResponse($glueResponseTransfer, $glueRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface $missingResource
     * @param \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication $apiApplication
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface $apiConvention
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function sendMissingResourceResponse(
        GlueRequestTransfer $glueRequestTransfer,
        MissingResourceInterface $missingResource,
        RequestFlowAwareApiApplication $apiApplication,
        ApiConventionPluginInterface $apiConvention
    ): GlueResponseTransfer {
        $glueResponseTransfer = $this->resourceExecutor->executeResource($missingResource, $glueRequestTransfer);
        $glueResponseTransfer = $apiConvention->formatResponse($glueResponseTransfer, $glueRequestTransfer);

        return $apiApplication->formatResponse($glueResponseTransfer, $glueRequestTransfer);
    }
}
