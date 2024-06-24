<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Updater;

use Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesMerchantCommission\Business\Expander\OrderExpanderInterface;
use Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface;

class OrderUpdater implements OrderUpdaterInterface
{
    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface
     */
    protected SalesMerchantCommissionToSalesFacadeInterface $salesFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Business\Expander\OrderExpanderInterface
     */
    protected OrderExpanderInterface $orderExpander;

    /**
     * @param \Spryker\Zed\SalesMerchantCommission\Dependency\Facade\SalesMerchantCommissionToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesMerchantCommission\Business\Expander\OrderExpanderInterface $orderExpander
     */
    public function __construct(
        SalesMerchantCommissionToSalesFacadeInterface $salesFacade,
        OrderExpanderInterface $orderExpander
    ) {
        $this->salesFacade = $salesFacade;
        $this->orderExpander = $orderExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer $merchantCommissionCalculationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function updateOrderItemsWithTotals(
        OrderTransfer $orderTransfer,
        MerchantCommissionCalculationResponseTransfer $merchantCommissionCalculationResponseTransfer
    ): OrderTransfer {
        $orderTransfer = $this->orderExpander->expandOrderWithMerchantCommissions(
            $orderTransfer,
            $merchantCommissionCalculationResponseTransfer,
        );

        $this->salesFacade->updateOrder($orderTransfer, $orderTransfer->getIdSalesOrderOrFail());

        return $orderTransfer;
    }
}
