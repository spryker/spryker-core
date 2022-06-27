<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\ConfigExtractorStrategy;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin\AuthorizationStrategyAwareResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

class AuthorizationStrategyAwareResourceRoutePluginConfigExtractorStrategy implements ConfigExtractorStrategyInterface
{
    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return bool
     */
    public function isApplicable(ResourceInterface $resource): bool
    {
        return $resource instanceof AuthorizationStrategyAwareResourceRoutePluginInterface;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer|null
     */
    public function extractRouteAuthorizationConfigTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        ResourceInterface $resource
    ): ?RouteAuthorizationConfigTransfer {
        $method = $glueRequestTransfer->getMethodOrFail();

        /** @var \Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin\AuthorizationStrategyAwareResourceRoutePluginInterface $authorizationStrategyAwareResourceRoutePlugin */
        $authorizationStrategyAwareResourceRoutePlugin = $resource;

        $routeAuthorizationConfigTransfers = $authorizationStrategyAwareResourceRoutePlugin->getRouteAuthorizationConfigurations();

        return $routeAuthorizationConfigTransfers[$method] ?? null;
    }
}
