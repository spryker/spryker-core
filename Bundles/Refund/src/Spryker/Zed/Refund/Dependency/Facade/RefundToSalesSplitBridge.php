<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund\Dependency\Facade;

class RefundToSalesSplit implements RefundToSalesSplitInterface
{


    /**
     * @var \Spryker\Zed\SalesSplit\Business\SalesSplitFacade SalesSplitFacade
     */
    protected $salesSplitFacade;

    /**
     * @param \Spryker\Zed\SalesSplit\Business\SalesSplitFacade $salesSplitFacade
     */
    public function __construct($salesSplitFacade)
    {
        $this->salesSplitFacade = $salesSplitFacade;
    }

    /**
     * @param int $idSalesOrderItem
     * @param int $quantity
     *
     *
     * @return \Generated\Shared\Transfer\ItemSplitResponseTransfer
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity)
    {
        return $this->salesSplitFacade->splitSalesOrderItem($idSalesOrderItem, $quantity);
    }

}
