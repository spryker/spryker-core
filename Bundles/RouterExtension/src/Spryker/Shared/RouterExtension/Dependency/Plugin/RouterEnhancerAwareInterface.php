<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\RouterExtension\Dependency\Plugin;

interface RouterEnhancerAwareInterface
{
    /**
     * Specification:
     * - Adds ability to use RouterEnhancerPlugins in your Router.
     * - Plugins will be added in the routers `getMatcher()` or `getGenerator()` methods.
     *
     * @api
     *
     * @param \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[] $routerEnhancerPlugins
     *
     * @return void
     */
    public function setRouterEnhancerPlugins(array $routerEnhancerPlugins): void;
}
