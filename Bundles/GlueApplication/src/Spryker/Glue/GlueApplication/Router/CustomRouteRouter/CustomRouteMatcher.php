<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\CustomRouteRouter;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\Exception\ControllerNotFoundException;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Resource\GenericResource;
use Spryker\Glue\GlueApplication\Resource\MissingResource;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder\RouterBuilderInterface;
use Spryker\Glue\GlueApplication\Router\RouteMatcherInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

class CustomRouteMatcher implements RouteMatcherInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder\RouterBuilderInterface
     */
    protected RouterBuilderInterface $routerBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder\RouterBuilderInterface $routerBuilder
     */
    public function __construct(RouterBuilderInterface $routerBuilder)
    {
        $this->routerBuilder = $routerBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    public function route(GlueRequestTransfer $glueRequestTransfer): ResourceInterface
    {
        $router = $this->routerBuilder->buildRouter($glueRequestTransfer->getApplicationOrFail());

        if (!$router) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        $glueRequestTransfer = $router->routeRequest($glueRequestTransfer);

        if (!$glueRequestTransfer->getResource()) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        if ($glueRequestTransfer->getResource()->getControllerExecutable()) {
            $controller = $glueRequestTransfer->getResource()->getControllerExecutable();
            /** @phpstan-var callable():\Generated\Shared\Transfer\GlueResponseTransfer $executable */
            $executable = $this->buildExecutable($controller);

            return new GenericResource($executable);
        }

        return new MissingResource(
            GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
            GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
        );
    }

    /**
     * @param array<string> $executable
     *
     * @return array<mixed>
     */
    protected function buildExecutable(array $executable): array
    {
        if (is_array($executable) && isset($executable[0]) && is_string($executable[0])) {
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
            /** @phpstan-var \Spryker\Glue\Kernel\Controller\AbstractController */
            $controllerInstance = new $controller();
            if ($controllerInstance instanceof AbstractController) {
                return new $controller();
            }
        }

        throw new ControllerNotFoundException('Controller not found!');
    }
}
