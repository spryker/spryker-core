<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;

/**
 * @method \Spryker\Zed\Router\Business\RouterFacadeInterface getFacade()
 * @method \Spryker\Zed\Router\RouterConfig getConfig()
 * @method \Spryker\Zed\Router\Communication\RouterCommunicationFactory getFactory()
 */
class MerchantPortalRouterApplicationPlugin extends AbstractRouterApplicationPlugin
{
    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function provideRouter(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_ROUTER, function () {
            return $this->getFacade()->getMerchantPortalChainRouter();
        });

        return $container;
    }
}
