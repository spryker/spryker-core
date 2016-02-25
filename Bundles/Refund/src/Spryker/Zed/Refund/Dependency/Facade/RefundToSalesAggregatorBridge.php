<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Dependency\Facade;

class RefundToSalesAggregator implements RefundToSalesAggregatorInterface
{
    /**
     * @var \Spryker\Zed\SalesAggregator\Business\SalesAggregatorFacade
     */
    protected $salesAggregatorFacade;

    /**
     * @param \Spryker\Zed\SalesAggregator\Business\SalesAggregatorFacade
     */
    public function __construct($salesAggregatorFacade)
    {
        $this->salesAggregatorFacade = $salesAggregatorFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\ItemSplitResponseTransfer
     */
    public function getOrderTotalsByIdSalesOrder($idSalesOrder)
    {
        return $this->salesAggregatorFacade->getOrderTotalsByIdSalesOrder($idSalesOrder);
    }
}
