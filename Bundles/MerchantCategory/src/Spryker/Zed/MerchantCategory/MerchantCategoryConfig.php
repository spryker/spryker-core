<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantCategoryConfig extends AbstractBundleConfig
{
    /**
     * Provides a limit of merchant categories that can be retrieved by one request.
     */
    public const MAX_CATEGORY_SELECT_COUNT = 1000;
}
