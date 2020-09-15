<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesOrderGui\Business\Reader\MerchantSalesOrderReader;
use Spryker\Zed\MerchantSalesOrderGui\Business\Reader\MerchantSalesOrderReaderInterface;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantSalesOrderFacadeInterface;
use Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiConfig getConfig()
 */
class MerchantSalesOrderGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrderGui\Business\Reader\MerchantSalesOrderReaderInterface
     */
    public function createMerchantSalesOrderReader(): MerchantSalesOrderReaderInterface
    {
        return new MerchantSalesOrderReader();
    }
}
