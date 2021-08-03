<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Cache;

use Spryker\Shared\Router\Cache\CacheInterface;
use Spryker\Zed\Router\Business\Router\ChainRouter;

class CacheWarmer implements CacheInterface
{
    /**
     * @var \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    protected $router;

    /**
     * @param \Spryker\Zed\Router\Business\Router\ChainRouter $router
     */
    public function __construct(ChainRouter $router)
    {
        $this->router = $router;
    }

    /**
     * @return void
     */
    public function warmUp(): void
    {
        $this->router->warmUp('');
    }
}
