<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class StorageConfig extends AbstractBundleConfig
{

    // todo move to client config
    const DEFAULT_REDIS_DATABASE = 0;
    const STORAGE_CACHE_STRATEGY_INCREMENTAL_KEY_SIZE_LIMIT = 1000;
    const STORAGE_CACHE_TTL = 86400;

}
