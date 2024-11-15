<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Checker;

use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class OrderChecker implements OrderCheckerInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\Checker\FlagCheckerInterface
     */
    protected FlagCheckerInterface $flagChecker;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected OmsRepositoryInterface $omsRepository;

    /**
     * @param \Spryker\Zed\Oms\Business\Checker\FlagCheckerInterface $flagChecker
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     */
    public function __construct(FlagCheckerInterface $flagChecker, OmsRepositoryInterface $omsRepository)
    {
        $this->flagChecker = $flagChecker;
        $this->omsRepository = $omsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $flag
     *
     * @return bool
     */
    public function areOrderItemsSatisfiedByFlag(OrderTransfer $orderTransfer, string $flag): bool
    {
        $orderItemFilterTransfer = $this->createOrderItemFilterTransfer($orderTransfer);
        $itemTransfers = $this->omsRepository->getOrderItems($orderItemFilterTransfer);

        return $this->flagChecker->hasOrderItemsFlag($itemTransfers, $flag);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderItemFilterTransfer
     */
    protected function createOrderItemFilterTransfer(OrderTransfer $orderTransfer): OrderItemFilterTransfer
    {
        $orderItemFilterTransfer = new OrderItemFilterTransfer();
        if ($orderTransfer->getOrderReference()) {
            return $orderItemFilterTransfer->addOrderReference($orderTransfer->getOrderReferenceOrFail());
        }

        return $orderItemFilterTransfer->addSalesOrderId($orderTransfer->getIdSalesOrderOrFail());
    }
}
