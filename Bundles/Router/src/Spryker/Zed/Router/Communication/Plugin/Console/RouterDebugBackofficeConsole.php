<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication\Plugin\Console;

use Symfony\Component\Routing\RouteCollection;

/**
 * @method \Spryker\Zed\Router\Business\RouterFacade getFacade()
 * @method \Spryker\Zed\Router\Communication\RouterCommunicationFactory getFactory()
 */
class RouterDebugBackofficeConsole extends AbstractRouterDebugConsole
{
    /**
     * @var string
     */
    protected const NAME = 'router:debug:backoffice';

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function getRouteCollection(): RouteCollection
    {
        return $this->getFacade()->getBackofficeChainRouter()->getRouteCollection();
    }
}
