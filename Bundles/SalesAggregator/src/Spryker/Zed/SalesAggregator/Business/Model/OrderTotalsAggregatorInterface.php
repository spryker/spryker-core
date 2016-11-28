<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\SalesAggregator\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderTotalsAggregatorInterface
{

    /**
     * @param int $idSalesAggregatorOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function aggregateByIdSalesAggregatorOrder($idSalesAggregatorOrder);

    /**
     * @param int $idSalesOrderItem
     *
     * @throws \Spryker\Zed\SalesAggregator\Business\Exception\OrderTotalHydrationException
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function aggregateByIdSalesAggregatorOrderItem($idSalesOrderItem);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function aggregateByOrderTransfer(OrderTransfer $orderTransfer);

}
