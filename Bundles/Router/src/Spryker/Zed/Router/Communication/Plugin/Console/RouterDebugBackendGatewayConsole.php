<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication\Plugin\Console;

use Symfony\Component\Routing\RouteCollection;

/**
 * @method \Spryker\Zed\Router\Business\RouterFacadeInterface getFacade()
 * @method \Spryker\Zed\Router\Communication\RouterCommunicationFactory getFactory()
 */
class RouterDebugBackendGatewayConsole extends AbstractRouterDebugConsole
{
    /**
     * @var string
     */
    protected const NAME = 'router:debug:backend-gateway';

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function getRouteCollection(): RouteCollection
    {
        return $this->getFacade()->getBackendGatewayChainRouter()->getRouteCollection();
    }
}
