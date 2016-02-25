<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Dependency\Facade;

class PayolutionToSalesAggregatorBridge implements PayolutionToSalesAggregatorInterface
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
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTotalsByIdSalesOrder($idSalesOrder)
    {
        return $this->salesAggregatorFacade->getOrderTotalsByIdSalesOrder($idSalesOrder);
    }

}
