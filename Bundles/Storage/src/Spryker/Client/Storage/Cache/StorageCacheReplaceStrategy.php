<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache;

class StorageCacheReplaceStrategy extends AbstractStorageCacheStrategy
{

    /**
     * @param string $cacheKey
     *
     * @return void
     */
    public function updateCache($cacheKey)
    {
        $isUpdateCacheNeeded = false;

        foreach ($this->getCachedKeys() as $key => $status) {
            if ($this->isUnusedKey($status)) {
                $this->unsetCachedKey($key);
            }

            if ($this->isUpdateNeeded($status)) {
                $isUpdateCacheNeeded = true;
            }
        }

        if ($isUpdateCacheNeeded) {
            $this->setCache($cacheKey);
        }
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    protected function isUpdateNeeded($status)
    {
        return $this->isNewKey($status) || $this->isUsedKey($status);
    }

}
