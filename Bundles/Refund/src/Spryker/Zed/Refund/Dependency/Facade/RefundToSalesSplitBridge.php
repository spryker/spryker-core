<?php
/**
 * (c) Spryker Systems GmbH copyright protected
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
