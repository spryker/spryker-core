<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Cache;

class ZedNavigationCacheRemover
{
    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface
     */
    protected $navigationCache;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface $navigationCache
     */
    public function __construct(ZedNavigationCacheInterface $navigationCache)
    {
        $this->navigationCache = $navigationCache;
    }

    /**
     * @return void
     */
    public function removeNavigationCache(): void
    {
        $this->navigationCache->removeCache();
    }
}
