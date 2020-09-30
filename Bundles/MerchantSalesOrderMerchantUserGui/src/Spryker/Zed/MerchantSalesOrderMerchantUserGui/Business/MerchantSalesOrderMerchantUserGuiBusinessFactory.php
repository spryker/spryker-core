<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business\Reader\MerchantSalesOrderReader;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business\Reader\MerchantSalesOrderReaderInterface;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\MerchantSalesOrderMerchantUserGuiConfig getConfig()
 */
class MerchantSalesOrderMerchantUserGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business\Reader\MerchantSalesOrderReaderInterface
     */
    public function createMerchantSalesOrderReader(): MerchantSalesOrderReaderInterface
    {
        return new MerchantSalesOrderReader();
    }
}
