<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return int
     */
    public function getDashboardNewOrdersDaysThreshold(): int
    {
        return 5;
    }
}
