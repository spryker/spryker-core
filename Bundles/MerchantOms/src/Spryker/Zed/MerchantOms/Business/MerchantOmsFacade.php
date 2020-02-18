<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business;

use Generated\Shared\Transfer\MerchantOmsEventTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface getRepository()
 */
class MerchantOmsFacade extends AbstractFacade implements MerchantOmsFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderResponseTransfer
     */
    public function dispatchNewMerchantOrderEvent(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantOmsEventDispatcher()
            ->dispatchNewMerchantOrderEvent($merchantOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     * @param \Generated\Shared\Transfer\MerchantOmsEventTransfer $merchantOmsEventTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function dispatchMerchantOrderItemEvent(
        MerchantOrderItemTransfer $merchantOrderItemTransfer,
        MerchantOmsEventTransfer $merchantOmsEventTransfer
    ): MerchantOrderItemResponseTransfer {
        return $this->getFactory()
            ->createMerchantOmsEventDispatcher()
            ->dispatchMerchantOrderItemEvent($merchantOrderItemTransfer, $merchantOmsEventTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantOmsEventTransfer $merchantOmsEventTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function dispatchMerchantOrderItemsEvent(
        MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer,
        MerchantOmsEventTransfer $merchantOmsEventTransfer
    ): MerchantOrderItemResponseTransfer {
        return $this->getFactory()
            ->createMerchantOmsEventDispatcher()
            ->dispatchMerchantOrderItemsEvent($merchantOrderItemCollectionTransfer, $merchantOmsEventTransfer);
    }
}
