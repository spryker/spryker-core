<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourcesProviderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig getConfig()
 */
class ResourcesProviderPlugin extends AbstractPlugin implements ResourcesProviderPluginInterface
{
    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_STOREFRONT_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'GLUE_STOREFRONT_API_APPLICATION';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getApplicationName(): string
    {
        return static::GLUE_STOREFRONT_API_APPLICATION;
    }

    /**
     * {@inheritDoc}
     * - Returns the stack of `\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface` for the current application.
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    public function getResources(): array
    {
        return $this->getFactory()->getResourcePlugins();
    }
}
