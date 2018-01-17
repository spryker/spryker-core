<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store;

use Spryker\Shared\Store\StoreConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class StoreConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isMultiStorePerZedEnabled()
    {
        return $this->get(StoreConstants::ENABLE_MULTI_STORE_PER_ZED, false);
    }
}
