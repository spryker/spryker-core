<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SearchHttpConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string|null
     */
    public function getSearchHttpSynchronizationPoolName(): ?string
    {
        return null;
    }
}
