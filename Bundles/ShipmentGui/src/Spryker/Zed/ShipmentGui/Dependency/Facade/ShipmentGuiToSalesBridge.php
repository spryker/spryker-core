<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

class ShipmentGuiToSalesBridge implements ShipmentGuiToSalesInterface
{
    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     */
    public function __construct($salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @api
     *
     * @inheritdoc
     */
    public function findOrderByIdSalesOrder(int $idSalesOrder): ?OrderTransfer
    {
        return $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);
    }
}
