<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Router;

use Psr\Log\LoggerInterface;
use Symfony\Cmf\Component\Routing\ChainRouter as SymfonyChainRouter;

class ChainRouter extends SymfonyChainRouter
{
    /**
     * @param \Spryker\Yves\RouterExtension\Dependency\Plugin\RouterPluginInterface[] $routerPlugins
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(array $routerPlugins, ?LoggerInterface $logger = null)
    {
        parent::__construct($logger);

        $this->addRouterPlugins($routerPlugins);
    }

    /**
     * @param \Spryker\Yves\RouterExtension\Dependency\Plugin\RouterPluginInterface[] $routerPlugins
     *
     * @return void
     */
    protected function addRouterPlugins(array $routerPlugins): void
    {
        foreach ($routerPlugins as $routerPlugin) {
            $this->add($routerPlugin->getRouter());
        }
    }
}
