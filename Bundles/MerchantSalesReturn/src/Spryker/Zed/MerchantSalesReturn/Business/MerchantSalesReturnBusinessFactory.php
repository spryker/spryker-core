<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesReturn\Business\Model\MerchantReturnPreparer;
use Spryker\Zed\MerchantSalesReturn\Business\Model\MerchantReturnPreparerInterface;

/**
 * @method \Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesReturn\Persistence\MerchantSalesReturnQueryContainer getQueryContainer()
 */
class MerchantSalesReturnBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesReturn\Business\Model\MerchantReturnPreparerInterface
     */
    public function createMerchantReturnPreparer(): MerchantReturnPreparerInterface
    {
        return new MerchantReturnPreparer();
    }

    public function createMerchantReturnValidator()
    {

    }
}
