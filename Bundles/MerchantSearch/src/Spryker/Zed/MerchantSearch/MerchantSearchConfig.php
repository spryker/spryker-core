<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantSearchConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string|null
     */
    public function getMerchantSearchSynchronizationPoolName(): ?string
    {
        return null;
    }
}
