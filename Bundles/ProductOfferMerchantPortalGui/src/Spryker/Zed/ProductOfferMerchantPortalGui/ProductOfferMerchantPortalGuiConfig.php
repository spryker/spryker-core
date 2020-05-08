<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getTableDefaultUiDateFormat(): string
    {
        return 'dd.MM.y';
    }

    /**
     * @api
     *
     * @return int[]
     */
    public function getTableDefaultAvailablePageSizes(): array
    {
        return [10, 25, 50, 100];
    }

    /**
     * @api
     *
     * @return int
     */
    public function getTableDefaultPageSize(): int
    {
        return 10;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getTableDefaultPage(): int
    {
        return 1;
    }
}
