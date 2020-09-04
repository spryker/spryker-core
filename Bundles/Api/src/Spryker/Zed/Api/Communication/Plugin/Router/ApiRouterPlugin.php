<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin\Router;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacadeInterface getFacade()
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface getQueryContainer()
 */
class ApiRouterPlugin extends AbstractPlugin implements RouterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getFacade()->getRouter();
    }
}
