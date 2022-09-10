<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\ResourceRouteBuilder;

use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class ResourceRouteBuilder implements ResourceRouteBuilderInterface
{
    /**
     * @var string
     */
    protected const DEFAULTS_CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const DEFAULTS_METHOD = '_method';

    /**
     * @var string
     */
    protected const FORMAT_RESOURCE_PATH = '/%s';

    /**
     * @var string
     */
    protected const FORMAT_RESOURCE_PATH_ID = '/%s/{id}';

    /**
     * @var string
     */
    protected const FORMAT_GETTER = 'get%s';

    /**
     * @var string
     */
    protected const RESOURCE_PREFIX = 'Resource';

    /**
     * @var array<string>
     */
    protected const RESOURCE_ID_METHODS = [
        'get', 'patch', 'delete',
    ];

    /**
     * @var array<string, string>
     */
    protected const ACTION_METHOD_MAP = [
        'get' => Request::METHOD_GET,
        'getCollection' => Request::METHOD_GET,
        'post' => Request::METHOD_POST,
        'patch' => Request::METHOD_PATCH,
        'delete' => Request::METHOD_DELETE,
    ];

    /**
     * {@inheritDoc}
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resourcePlugin
     *
     * @return array<string, \Symfony\Component\Routing\Route>
     */
    public function buildRoutes(ResourceInterface $resourcePlugin): array
    {
        $resourceType = $resourcePlugin->getType();
        $resourceController = $resourcePlugin->getController();

        $routes = [];
        $declaredMethods = $resourcePlugin->getDeclaredMethods()->toArray(false, true);
        foreach ($declaredMethods as $declaredMethod => $declaredMethodTransfer) {
            if ($declaredMethodTransfer === null) {
                continue;
            }

            $routeTypeKey = sprintf('%s%s%s', $resourceType, static::RESOURCE_PREFIX, ucfirst($declaredMethod));
            $path = $this->getResourceMethodPath($resourceType, $declaredMethod);
            $httpMethod = $this->getHttpMethod($declaredMethod);

            $routes[$routeTypeKey] = $this->buildRoute($declaredMethodTransfer, $resourceController, $path, $httpMethod);
        }

        return $routes;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer $declaredMethodTransfer
     * @param string $resourceController
     * @param string $path
     * @param string $httpMethod
     *
     * @return \Symfony\Component\Routing\Route
     */
    protected function buildRoute(
        GlueResourceMethodConfigurationTransfer $declaredMethodTransfer,
        string $resourceController,
        string $path,
        string $httpMethod
    ): Route {
        $controller = $declaredMethodTransfer->getController() ?? $resourceController;

        return (new Route($path))
            ->setMethods($httpMethod)
            ->setDefault(static::DEFAULTS_CONTROLLER, [
                $controller,
                $declaredMethodTransfer->getAction(),
            ])
            ->setDefault(static::DEFAULTS_METHOD, $httpMethod);
    }

    /**
     * @param string $resourceType
     * @param string $declaredMethod
     *
     * @return string
     */
    protected function getResourceMethodPath(string $resourceType, string $declaredMethod): string
    {
        if (in_array($declaredMethod, static::RESOURCE_ID_METHODS)) {
            return sprintf(static::FORMAT_RESOURCE_PATH_ID, $resourceType);
        }

        return sprintf(static::FORMAT_RESOURCE_PATH, $resourceType);
    }

    /**
     * @param string $declaredMethod
     *
     * @return string
     */
    protected function getHttpMethod(string $declaredMethod): string
    {
        if (!isset(static::ACTION_METHOD_MAP[$declaredMethod])) {
            return Request::METHOD_GET;
        }

        return static::ACTION_METHOD_MAP[$declaredMethod];
    }
}
