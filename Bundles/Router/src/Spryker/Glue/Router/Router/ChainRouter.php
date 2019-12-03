<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Router\Router;

use Psr\Log\LoggerInterface;
use Spryker\Glue\RouterExtension\Dependency\Plugin\RouterPluginInterface;
use Symfony\Cmf\Component\Routing\ChainRouter as SymfonyChainRouter;

class ChainRouter extends SymfonyChainRouter
{
    /**
     * @param \Spryker\Glue\RouterExtension\Dependency\Plugin\RouterPluginInterface[] $routerPlugins
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(array $routerPlugins, ?LoggerInterface $logger = null)
    {
        parent::__construct($logger);

        $this->addRouterPlugins($routerPlugins);
    }

    /**
     * @param \Spryker\Glue\RouterExtension\Dependency\Plugin\RouterPluginInterface[] $routerPlugins
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
}
