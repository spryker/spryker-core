<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionFactory getFactory()
 */
class JsonApiApiConventionPlugin extends AbstractPlugin implements ApiConventionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if JSON:API convention is used by the current request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(GlueRequestTransfer $glueRequestTransfer): bool
    {
        $meta = $glueRequestTransfer->getMeta();

        return array_key_exists('content-type', $meta)
            && isset($meta['content-type'][0])
            && $meta['content-type'][0] === GlueJsonApiConventionConfig::HEADER_CONTENT_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Returns JSON API convention name.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return GlueJsonApiConventionConfig::CONVENTION_JSON_API;
    }

    /**
     * {@inheritDoc}
     * - Returns JSON API resource type.
     *
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return JsonApiResourceInterface::class;
    }

    /**
     * {@inheritDoc}
     * - Builds request according to the JSON API Convention.
     * - Expands `GlueRequestTransfer` with JSON API Convention name.
     * - Executes a stack of {@link \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface} plugins.
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
     * - Executes a stack of {@link \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestValidatorPluginInterface} plugins.
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
     * - Executes a stack of {@link \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface} plugins.
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
     * - Executes a stack of {@link \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface} plugins.
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
