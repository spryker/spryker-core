<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Resource\MissingResource;
use Spryker\Glue\GlueApplication\Resource\PreFlightResource;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;

class RouteMatcherCollection implements RouteMatcherInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected GlueApplicationConfig $glueApplicationConfig;

    /**
     * @var array<\Spryker\Glue\GlueApplication\Router\RouteMatcherInterface>
     */
    protected array $routeMatchers;

    /**
     * @param array<\Spryker\Glue\GlueApplication\Router\RouteMatcherInterface> $routeMatchers
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $glueApplicationConfig
     */
    public function __construct(
        array $routeMatchers,
        GlueApplicationConfig $glueApplicationConfig
    ) {
        $this->routeMatchers = $routeMatchers;
        $this->glueApplicationConfig = $glueApplicationConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    public function route(GlueRequestTransfer $glueRequestTransfer): ResourceInterface
    {
        foreach ($this->routeMatchers as $routeMatcherType => $routeMatcher) {
            if (!in_array($routeMatcherType, $this->glueApplicationConfig->getRouteMatchers())) {
                continue;
            }

            $resourcePlugin = $routeMatcher->route($glueRequestTransfer);

            if (!$resourcePlugin instanceof MissingResourceInterface) {
                if (
                    $glueRequestTransfer->getMethod() === Request::METHOD_OPTIONS &&
                    !$resourcePlugin->getDeclaredMethods()->getOptions()
                ) {
                    return new PreFlightResource($resourcePlugin);
                }

                return $resourcePlugin;
            }
        }

        return new MissingResource(
            GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
            GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
        );
    }
}
