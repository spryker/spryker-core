<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\CustomRouteRouter;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\Exception\ExceptionInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router as SymfonyRouter;

class Router extends SymfonyRouter implements RouterInterface, WarmableInterface
{
    /**
     * @var \Symfony\Component\Config\ConfigCacheFactoryInterface|null
     */
    protected $configCacheFactory;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function routeRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $this->setContext(
            (new RequestContext())
                ->setPathInfo($glueRequestTransfer->getPathOrFail())
                ->setMethod($glueRequestTransfer->getMethodOrFail())
                ->setHost($glueRequestTransfer->getHostOrFail())
                ->setQueryString($glueRequestTransfer->getParametersString() ?? ''),
        );

        try {
            $matchResult = $this->match($glueRequestTransfer->getPathOrFail());
        } catch (ExceptionInterface $exception) {
            return $glueRequestTransfer;
        }

        $glueResourceTransfer = (new GlueResourceTransfer())
            ->setMethod($matchResult['_method'] ?? $glueRequestTransfer->getMethod())
            ->setRoute($matchResult['_route'] ?? null)
            ->setControllerExecutable($matchResult['_controller'] ?? null)
            ->setAction($matchResult['_action'] ?? null)
            ->setScope($matchResult['scope'] ?? null)
            ->setId($matchResult['id'] ?? null)
            ->setParameters($matchResult);

        $glueRequestTransfer->setResource($glueResourceTransfer);

        return $glueRequestTransfer;
    }

    /**
     * Provides the ConfigCache factory implementation, falling back to a
     * default implementation if necessary.
     *
     * @return \Symfony\Component\Config\ConfigCacheFactoryInterface
     */
    protected function getConfigCacheFactory()
    {
        if ($this->configCacheFactory === null) {
            $this->configCacheFactory = new ConfigCacheFactory($this->options['debug']);
        }

        return $this->configCacheFactory;
    }

    /**
     * @param string $cacheDir
     *
     * @return array<string>
     */
    public function warmUp(string $cacheDir): array
    {
        $this->getGenerator();
        $this->getMatcher();

        return [];
    }
}
