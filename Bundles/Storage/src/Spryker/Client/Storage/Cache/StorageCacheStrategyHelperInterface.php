<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache;

interface StorageCacheStrategyHelperInterface
{

    /**
     * @param string $cacheKey
     *
     * @return void
     */
    public function setCache($cacheKey);

    /**
     * @return array
     */
    public function getCachedKeys();

    /**
     * @param string $key
     *
     * @return void
     */
    public function unsetCachedKey($key);

    /**
     * @return void
     */
    public function unsetLastCachedKey();

    /**
     * @param string $status
     *
     * @return bool
     */
    public function isNewKey($status);

    /**
     * @param string $status
     *
     * @return bool
     */
    public function isUsedKey($status);

    /**
     * @param string $status
     *
     * @return bool
     */
    public function isUnusedKey($status);

}
