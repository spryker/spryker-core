<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;

/**
 * Provides extension capabilities to routes by authorization strategy.
 */
interface DefaultAuthorizationStrategyAwareResourceRoutePluginInterface
{
    /**
     * Specification:
     * - Provides the authorization default config transfers for the resource routes.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer
     */
    public function getRouteAuthorizationDefaultConfiguration(): RouteAuthorizationConfigTransfer;
}
