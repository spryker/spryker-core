<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Symfony\Component\Routing\RouteCollection;

/**
 * Provides capability to extends routes collection.
 */
interface ApiApplicationEndpointProviderPluginInterface
{
    /**
     * Specification:
     * - Gets route collection from current Glue Application.
     *
     * @api
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection(): RouteCollection;

    /**
     * Specification:
     * - Gets a Glue Application name to be shown as capture for endpoints.
     *
     * @api
     *
     * @return string
     */
    public function getApiApplicationName(): string;
}
