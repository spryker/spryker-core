<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Router;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig;
use Spryker\Glue\GlueStorefrontApiApplication\Resource\GenericResource;
use Spryker\Glue\GlueStorefrontApiApplication\Resource\MissingResource;
use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface;

class RequestRoutingMatcher implements RequestRoutingMatcherInterface
{
    /**
     * @var \Spryker\Glue\GlueStorefrontApiApplication\Router\ChainRouterInterface
     */
    protected ChainRouterInterface $chainRouter;

    /**
     * @var \Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface
     */
    protected RequestResourceFilterPluginInterface $requestResourceFilterPlugin;

    /**
     * @param \Spryker\Glue\GlueStorefrontApiApplication\Router\ChainRouterInterface $chainRouter
     * @param \Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface $requestResourceFilterPlugin
     */
    public function __construct(
        ChainRouterInterface $chainRouter,
        RequestResourceFilterPluginInterface $requestResourceFilterPlugin
    ) {
        $this->chainRouter = $chainRouter;
        $this->requestResourceFilterPlugin = $requestResourceFilterPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resources
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    public function matchRequest(GlueRequestTransfer $glueRequestTransfer, array $resources): ResourceInterface
    {
        $glueRequestTransfer = $this->chainRouter->routeResource($glueRequestTransfer);

        if (!$glueRequestTransfer->getResource()) {
            return new MissingResource(
                GlueStorefrontApiApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueStorefrontApiApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        if (
            !$glueRequestTransfer->getResource()->getResourceName() &&
            $glueRequestTransfer->getResource()->getController()
        ) {
            /** @var callable $controller */
            $controller = $glueRequestTransfer->getResource()->getController();

            return new GenericResource($controller);
        }

        $resource = $this->requestResourceFilterPlugin->filterResource($glueRequestTransfer, $resources);

        if (!$resource) {
            return new MissingResource(
                GlueStorefrontApiApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueStorefrontApiApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        return $resource;
    }
}
