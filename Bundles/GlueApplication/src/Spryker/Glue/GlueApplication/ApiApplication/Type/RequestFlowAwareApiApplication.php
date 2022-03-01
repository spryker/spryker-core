<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\ApiApplication\Type;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\Kernel\FactoryResolverAwareTrait;
use Spryker\Shared\Application\Application;

/**
 * ApiApplication classes extending this class are not plugins, they extend {@link \Spryker\Shared\Application\Application}
 * to hold the execution flow of the API application. This abstract class makes it easy for new API applications to follow the
 * standardized GlueApplication request flow using Spryker transfer objects as input/output.
 */
abstract class RequestFlowAwareApiApplication extends Application
{
    use FactoryResolverAwareTrait;

    /**
     * Specification:
     * - Builds the request by extracting transport and format specific fields (e.g.: HTTP headers to GlueRequestTransfer.meta).
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    abstract public function buildRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer;

    /**
     * Specification:
     * - Executes validation specific to the application.
     *
     * @see {@link \Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication::buildRequest()}
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    abstract public function validateRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer;

    /**
     * Specification:
     * - Routes the `GlueRequestTransfer` against the `ResourceInterface` plugins wired for the application.
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    abstract public function route(GlueRequestTransfer $glueRequestTransfer): ResourceInterface;

    /**
     * Specification:
     * - Executes validations that need to be aware of the resolved route.
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    abstract public function validateRequestAfterRouting(
        GlueRequestTransfer $glueRequestTransfer,
        ResourceInterface $resource
    ): GlueRequestValidationTransfer;

    /**
     * Specification:
     * - Formats the response in an application-specific way.
     *
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    abstract public function formatResponse(GlueResponseTransfer $glueResponseTransfer, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;
}
