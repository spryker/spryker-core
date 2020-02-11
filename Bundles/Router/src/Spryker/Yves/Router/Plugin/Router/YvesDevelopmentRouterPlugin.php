<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\Router;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\RouterExtension\Dependency\Plugin\RouterPluginInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \Spryker\Yves\Router\RouterConfig getConfig()
 * @method \Spryker\Yves\Router\RouterFactory getFactory()
 */
class YvesDevelopmentRouterPlugin extends AbstractPlugin implements RouterPluginInterface
{
    /**
     * @api
     *
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getFactory()->createYvesDevelopmentRouter();
    }
}
