<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentDashboardMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AgentDashboardMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const DEFAULT_MERCHANT_USER_TABLE_PAGE_SIZE = 10;

    /**
     * Specification:
     * - Returns the default page size for the merchant user table.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultMerchantUserTablePageSize(): int
    {
        return static::DEFAULT_MERCHANT_USER_TABLE_PAGE_SIZE;
    }
}
