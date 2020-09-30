<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountMerchantSalesOrder\Communication\Plugin;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPreExpandPluginInterface;

/**
 * @method \Spryker\Zed\DiscountMerchantSalesOrder\Business\DiscountMerchantSalesOrderFacade getFacade()
 */
class FilterDiscountMerchantOrderPreExpandPlugin extends AbstractPlugin implements MerchantOrderPreExpandPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters out discounts in MerchantOrderTransfer.order that does not belongs to the current merchant order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function execute(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        return $this->getFacade()
            ->filterMerchantDiscounts($merchantOrderTransfer);
    }
}
