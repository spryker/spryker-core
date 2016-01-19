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
     * @var SalesFacade
     */
    protected $salesFacade;

    /**
     * @param SalesFacade $salesFacade
     */
    public function __construct($salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return OrderTransfer
     */
    public function getOrderTotalsByIdSalesOrder($idSalesOrder)
    {
        return $this->salesFacade->getOrderTotalsByIdSalesOrder($idSalesOrder);
    }

}
