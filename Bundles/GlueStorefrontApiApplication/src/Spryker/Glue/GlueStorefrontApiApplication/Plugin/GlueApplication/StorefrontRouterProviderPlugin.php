<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiApplicationEndpointProviderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\Routing\RouteCollection;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
class StorefrontRouterProviderPlugin extends AbstractPlugin implements ApiApplicationEndpointProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets route collection from current Glue Storefront API Application.
     *
     * @api
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection(): RouteCollection
    {
        return $this->getFactory()->createChainRouter()->getRouteCollection();
    }

    /**
     * {@inheritDoc}
     * - Returns name of Glue Storefront API Application.
     *
     * @api
     *
     * @return string
     */
    public function getApiApplicationName(): string
    {
        return 'GlueStorefrontApiApplication';
    }
}
