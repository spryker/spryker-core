<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin;

/**
 * Provides extension capabilities to routes by authorization strategy per method.
 */
interface AuthorizationStrategyAwareResourceRoutePluginInterface
{
    /**
     * Specification:
     * - Provides the authorization config transfers for the resource routes.
     * - Sets null for method if you want to skip validation.
     * - Example ['method' => $routeAuthorizationConfigTransfer, 'methodToSkip' => null].
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\RouteAuthorizationConfigTransfer>
     */
    public function getRouteAuthorizationConfigurations(): array;
}
