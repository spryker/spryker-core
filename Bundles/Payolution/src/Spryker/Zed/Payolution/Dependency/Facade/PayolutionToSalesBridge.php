<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\SalesFacade;

class PayolutionToSalesBridge implements PayolutionToSalesInterface
{

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacade
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacade $salesFacade
     */
    public function __construct($salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTotalsByIdSalesOrder($idSalesOrder)
    {
        return $this->salesFacade->getOrderTotalsByIdSalesOrder($idSalesOrder);
    }

}
