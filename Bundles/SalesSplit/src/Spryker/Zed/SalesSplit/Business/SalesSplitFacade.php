<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesSplit\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesSplit\Business\SalesSplitBusinessFactory getFactory()
 */
class SalesSplitFacade extends AbstractFacade implements SalesSplitFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ItemSplitResponseTransfer
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity)
    {
        return $this->getFactory()->createOrderItemSplitter()->split($idSalesOrderItem, $quantity);
    }
}
