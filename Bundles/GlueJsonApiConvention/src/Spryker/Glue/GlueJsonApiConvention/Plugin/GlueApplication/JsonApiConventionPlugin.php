<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionFactory getFactory()
 */
class JsonApiConventionPlugin extends AbstractPlugin implements ConventionPluginInterface
{
    /**
     * @var string
     */
    protected const HEADER_ACCEPT = 'accept';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'content-type';

    /**
     * {@inheritDoc}
     * - Returns true if the `ContentType` header is present and is equal to JSON:API mime-type "application/vnd.api+json"
     * or if the `Accept` header is present and is equal to JSON:API mime-type "application/vnd.api+json" and the request has GET type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(GlueRequestTransfer $glueRequestTransfer): bool
    {
        if ($this->isApplicableByContentTypeHeader($glueRequestTransfer)) {
            return true;
        }

        return $this->isApplicableByAcceptHeader($glueRequestTransfer);
    }

    /**
     * {@inheritDoc}
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
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    public function provideRequestBuilderPlugins(): array
    {
        return $this->getFactory()->getRequestBuilderPlugins();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function provideRequestValidatorPlugins(): array
    {
        return $this->getFactory()->getRequestValidatorPlugins();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    public function provideRequestAfterRoutingValidatorPlugins(): array
    {
        return $this->getFactory()->getRequestAfterRoutingValidatorPlugins();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    public function provideResponseFormatterPlugins(): array
    {
        return $this->getFactory()->getResponseFormatterPlugins();
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function isApplicableByAcceptHeader(GlueRequestTransfer $glueRequestTransfer): bool
    {
        $meta = $glueRequestTransfer->getMeta();

        if (
            array_key_exists(static::HEADER_ACCEPT, $meta)
            && isset($meta[static::HEADER_ACCEPT][0])
            && $meta[static::HEADER_ACCEPT][0] === GlueJsonApiConventionConfig::HEADER_CONTENT_TYPE
            && $glueRequestTransfer->getMethodOrFail() === Request::METHOD_GET
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function isApplicableByContentTypeHeader(GlueRequestTransfer $glueRequestTransfer): bool
    {
        $meta = $glueRequestTransfer->getMeta();

        if (
            array_key_exists(static::HEADER_CONTENT_TYPE, $meta)
            && isset($meta[static::HEADER_CONTENT_TYPE][0])
            && $meta[static::HEADER_CONTENT_TYPE][0] === GlueJsonApiConventionConfig::HEADER_CONTENT_TYPE
        ) {
            return true;
        }

        return false;
    }
}
