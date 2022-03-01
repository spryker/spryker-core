<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Router;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouterPluginInterface;
use Symfony\Cmf\Component\Routing\ChainRouter as SymfonyChainRouter;
use Symfony\Component\Routing\Exception\ExceptionInterface;
use Symfony\Component\Routing\RequestContext;

class ChainRouter extends SymfonyChainRouter implements ChainRouterInterface
{
    /**
     * @param array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouterPluginInterface> $routerPlugins
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(array $routerPlugins, ?LoggerInterface $logger = null)
    {
        parent::__construct($logger);

        $this->addRouterPlugins($routerPlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function routeResource(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
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
            ->setResourceName($matchResult['_resourceName'] ?? null)
            ->setMethod($matchResult['_method'] ?? null)
            ->setController($matchResult['_controller'] ?? null)
            ->setAction($matchResult['_action'] ?? null)
            ->setParameters($this->filterParameters($matchResult));

        $glueRequestTransfer->setResource($glueResourceTransfer);

        return $glueRequestTransfer;
    }

    /**
     * @param array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouterPluginInterface> $routerPlugins
     *
     * @return void
     */
    protected function addRouterPlugins(array $routerPlugins): void
    {
        foreach ($routerPlugins as $routerPlugin) {
            if ($routerPlugin instanceof RouterPluginInterface) {
                $routerPlugin = $routerPlugin->getRouter();
            }

            $this->add($routerPlugin);
        }
    }

    /**
     * @param array<mixed> $matchResult
     *
     * @return array<mixed>
     */
    protected function filterParameters(array $matchResult): array
    {
        return array_filter(
            $matchResult,
            function (string $parameter) {
                return strpos($parameter, '_') === 0;
            },
            ARRAY_FILTER_USE_KEY,
        );
    }
}
