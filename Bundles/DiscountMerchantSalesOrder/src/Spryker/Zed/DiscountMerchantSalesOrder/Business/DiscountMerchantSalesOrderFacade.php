<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountMerchantSalesOrder\Business;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DiscountMerchantSalesOrder\Business\DiscountMerchantSalesOrderBusinessFactory getFactory()
 */
class DiscountMerchantSalesOrderFacade extends AbstractFacade implements DiscountMerchantSalesOrderFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function filterMerchantDiscounts(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        return $this->getFactory()
            ->createMerchantOrderDiscountFilter()
            ->filterMerchantDiscounts($merchantOrderTransfer);
    }
}
