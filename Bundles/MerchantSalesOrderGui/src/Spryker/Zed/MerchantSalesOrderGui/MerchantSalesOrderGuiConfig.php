<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantSalesOrderGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\MerchantSalesOrderGui\Communication\Controller\DetailController::indexAction()
     */
    public const URL_DETAIL = '/merchant-sales-order-gui/detail';
    public const REQUEST_ID_MERCHANT_SALES_ORDER = 'id-merchant-sales-order-gui';
}
