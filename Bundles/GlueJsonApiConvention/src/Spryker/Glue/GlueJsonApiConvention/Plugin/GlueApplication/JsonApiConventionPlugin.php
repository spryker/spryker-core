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

/**
 * @method \Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionFactory getFactory()
 */
class JsonApiConventionPlugin extends AbstractPlugin implements ConventionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if the `ContentType` header is present and is equal to JSON:API mime-type "application/vnd.api+json".
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
}
