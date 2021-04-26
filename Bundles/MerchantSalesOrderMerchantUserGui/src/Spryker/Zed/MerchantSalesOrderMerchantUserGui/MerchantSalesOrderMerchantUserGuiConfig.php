<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantSalesOrderMerchantUserGuiConfig extends AbstractBundleConfig
{
    /**
     * This method provides list of urls to render blocks inside order detail page.
     * URL defines path to external bundle controller.
     * Action should return return array or redirect response.
     *
     * example:
     * [
     *    'key' => 'controller path',
     * ]
     *
     * @api
     *
     * @return string[]
     */
    public function getMerchantSalesOrderDetailExternalBlocksUrls(): array
    {
        return [];
    }
}
