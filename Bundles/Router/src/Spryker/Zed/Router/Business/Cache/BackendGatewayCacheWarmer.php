<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Cache;

class BackendGatewayCacheWarmer extends AbstractCacheWarmer
{
    /**
     * @return string|null
     */
    protected function getCacheDir(): ?string
    {
        return $this->config->getBackendGatewayRouterConfiguration()['cache_dir'] ?? null;
    }
}
