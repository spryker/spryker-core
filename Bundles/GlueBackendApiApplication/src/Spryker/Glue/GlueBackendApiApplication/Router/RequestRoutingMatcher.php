<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Router;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\Exception\ControllerNotFoundException;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig;
use Spryker\Glue\GlueBackendApiApplication\Resource\GenericResource;
use Spryker\Glue\GlueBackendApiApplication\Resource\MissingResource;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

class RequestRoutingMatcher implements RequestRoutingMatcherInterface
{
    /**
     * @var \Spryker\Glue\GlueBackendApiApplication\Router\ChainRouterInterface
     */
    protected ChainRouterInterface $chainRouter;

    /**
     * @var \Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface
     */
    protected RequestResourceFilterPluginInterface $requestResourceFilterPlugin;

    /**
     * @param \Spryker\Glue\GlueBackendApiApplication\Router\ChainRouterInterface $chainRouter
     * @param \Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface $requestResourceFilterPlugin
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
                GlueBackendApiApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueBackendApiApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        if (
            !$glueRequestTransfer->getResource()->getResourceName() &&
            $glueRequestTransfer->getResource()->getContollerExecutable()
        ) {
            /** @var callable $executable */
            $executable = $this->buildExecutable($glueRequestTransfer->getResource()->getContollerExecutable());

            return new GenericResource($executable);
        }

        $resource = $this->requestResourceFilterPlugin->filterResource($glueRequestTransfer, $resources);

        if (!$resource) {
            return new MissingResource(
                GlueBackendApiApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueBackendApiApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        return $resource;
    }

    /**
     * @param array<string> $executable
     *
     * @return array<mixed>
     */
    protected function buildExecutable(array $executable): array
    {
        if (isset($executable[0]) && is_string($executable[0])) {
            $executable[0] = $this->createControllerInstance($executable[0]);
        }

        return $executable;
    }

    /**
     * @param string $controller
     *
     * @throws \Spryker\Glue\GlueApplication\Exception\ControllerNotFoundException
     *
     * @return \Spryker\Glue\Kernel\Controller\AbstractController
     */
    protected function createControllerInstance(string $controller): AbstractController
    {
        if (class_exists($controller)) {
            /** @var \Spryker\Glue\Kernel\Controller\AbstractController $controller */
            $controller = new $controller();

            return new $controller();
        }

        throw new ControllerNotFoundException(
            sprintf('Controller not found: %s', $controller),
        );
    }
}
