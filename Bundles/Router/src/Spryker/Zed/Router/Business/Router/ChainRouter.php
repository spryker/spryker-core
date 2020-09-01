<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Router;

use Psr\Log\LoggerInterface;
use Symfony\Cmf\Component\Routing\ChainRouter as SymfonyChainRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

class ChainRouter extends SymfonyChainRouter
{
    /**
     * @param \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface[] $routerPlugins
     * @param \Psr\Log\LoggerInterface|null $logger
     * @param \Symfony\Component\Routing\RequestContext|null $requestContext
     */
    public function __construct(array $routerPlugins, ?LoggerInterface $logger = null, ?RequestContext $requestContext = null)
    {
        parent::__construct($logger);

        $this->addRequestContext($requestContext);
        $this->addRouterPlugins($routerPlugins);
    }

    /**
     * @param \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface[] $routerPlugins
     *
     * @return void
     */
    protected function addRouterPlugins(array $routerPlugins): void
    {
        foreach ($routerPlugins as $routerPlugin) {
            $this->add($routerPlugin->getRouter());
        }
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext|null $requestContext
     *
     * @return void
     */
    protected function addRequestContext(?RequestContext $requestContext = null): void
    {
        $request = Request::createFromGlobals();

        if (!$requestContext) {
            $requestContext = new RequestContext();
        }
        $requestContext->fromRequest($request);

        $this->setContext($requestContext);
    }
}
