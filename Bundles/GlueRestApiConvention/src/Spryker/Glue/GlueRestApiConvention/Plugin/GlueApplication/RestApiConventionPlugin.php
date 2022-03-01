<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionFactory getFactory()
 */
class RestApiConventionPlugin extends AbstractPlugin implements ApiConventionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true, thus needs to be wired the last one.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(GlueRequestTransfer $glueRequestTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * - Returns REST API convention name.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return GlueRestApiConventionConfig::CONVENTION_REST_API;
    }

    /**
     * {@inheritDoc}
     * - Returns REST API Resource type.
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return RestResourceInterface::class;
    }

    /**
     * {@inheritDoc}
     * - Builds request according to the Rest API convention.
     * - Expands `GlueRequestTransfer` with REST API convention name.
     * - Runs a stack of `\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface` plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $glueRequestTransfer->setConvention($this->getName());

        foreach ($this->getFactory()->getRequestBuilderPlugins() as $builderRequestPlugin) {
            $glueRequestTransfer = $builderRequestPlugin->build($glueRequestTransfer);
        }

        return $glueRequestTransfer;
    }

    /**
     * {@inheritDoc}
     * - Validates the request according to the REST API convention.
     * - Executes a stack of {@link \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestValidatorPluginInterface} plugins.
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

        return $glueRequestValidationTransfer ?? new GlueRequestValidationTransfer();
    }

    /**
     * {@inheritDoc}
     * - Validates the request after routing step according to the REST API convention.
     * - Executes a stack of {@link \Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface} plugins.
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
     * - Formats the response according to the REST API convention.
     * - Runs a stack of `\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface` plugins.
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
}
