<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StorageRouter\Plugin\Router;

use Spryker\Shared\RouterExtension\Dependency\Plugin\RouterPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \Spryker\Yves\StorageRouter\StorageRouterFactory getFactory()
 */
class StorageRouterPlugin extends AbstractPlugin implements RouterPluginInterface
{
    /**
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getFactory()->createRouter();
    }
}
