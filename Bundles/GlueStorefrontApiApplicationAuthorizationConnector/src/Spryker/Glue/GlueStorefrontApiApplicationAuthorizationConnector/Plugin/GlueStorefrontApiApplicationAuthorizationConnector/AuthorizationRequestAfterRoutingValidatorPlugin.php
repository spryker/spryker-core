<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Plugin\GlueStorefrontApiApplicationAuthorizationConnector;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorFactory getFactory()
 */
class AuthorizationRequestAfterRoutingValidatorPlugin extends AbstractPlugin implements RequestAfterRoutingValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Skips validation for OPTIONS requests.
     * - Performs the authorization check on the resource.
     * - Resource can implement `AuthorizationStrategyAwareResourceRoutePluginInterface` or `DefaultAuthorizationStrategyAwareResourceRoutePluginInterface`.
     * - If the plugins have implemented one of these interfaces, the `RouteAuthorizationConfigTransfer` configuration is used.
     * - `RouteAuthorizationConfigTransfer.strategy` is required. Without a strategy the authorization check can't be executed and return default `GlueRequestValidationTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer, ResourceInterface $resource): GlueRequestValidationTransfer
    {
        return $this->getFactory()->createAuthorizationValidator()->validate($glueRequestTransfer, $resource);
    }
}
