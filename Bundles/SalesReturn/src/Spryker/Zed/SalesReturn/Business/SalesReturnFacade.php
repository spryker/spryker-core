<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesReturn\Business\SalesReturnBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface getRepository()
 */
class SalesReturnFacade extends AbstractFacade implements SalesReturnFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function getReturnReasons(ReturnReasonFilterTransfer $returnReasonFilterTransfer): ReturnReasonCollectionTransfer
    {
        return $this->getRepository()->getReturnReasonCollectionByFilter($returnReasonFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturns(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer
    {
        return $this->getFactory()
            ->createReturnReader()
            ->getReturnCollection($returnFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnResponseTransfer
    {
        return $this->getFactory()
            ->createReturnWriter()
            ->createReturn($returnCreateRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function setOrderItemRemunerationAmount(ItemTransfer $itemTransfer): void
    {
        $this->getFactory()
            ->createItemRemunerationAmountSetter()
            ->setOrderItemRemunerationAmount($itemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderTotalsWithRemunerationTotal(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createOrderRemunerationTotalExpander()
            ->expandOrderTotalsWithRemunerationTotal($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function setOrderItemIsReturnableByGlobalReturnableNumberOfDays(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createIsReturnableSetter()
            ->setOrderItemIsReturnableByGlobalReturnableNumberOfDays($itemTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function setOrderItemIsReturnableByItemState(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createIsReturnableSetter()
            ->setOrderItemIsReturnableByItemState($itemTransfers);
    }
}
