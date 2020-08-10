<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOmsTriggerResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;

class MerchantSalesOrderMerchantUserGuiToMerchantOmsFacadeBridge implements MerchantSalesOrderMerchantUserGuiToMerchantOmsFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface
     */
    protected $merchantOmsFacade;

    /**
     * @param \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface $merchantOmsFacade
     */
    public function __construct($merchantOmsFacade)
    {
        $this->merchantOmsFacade = $merchantOmsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     **/
    public function expandMerchantOrderItemsWithStateHistory(
        MerchantOrderTransfer $merchantOrderTransfer
    ): MerchantOrderTransfer {
        return $this->merchantOmsFacade->expandMerchantOrderItemsWithStateHistory($merchantOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     **/
    public function expandMerchantOrderWithMerchantOmsData(
        MerchantOrderTransfer $merchantOrderTransfer
    ): MerchantOrderTransfer {
        return $this->merchantOmsFacade->expandMerchantOrderWithMerchantOmsData($merchantOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer
     */
    public function expandMerchantOrderItemsWithManualEvents(
        MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
    ): MerchantOrderItemCollectionTransfer {
        return $this->merchantOmsFacade->expandMerchantOrderItemsWithManualEvents($merchantOrderItemCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     *
     * @return int
     */
    public function triggerEventForMerchantOrderItems(MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer): int
    {
        return $this->merchantOmsFacade->triggerEventForMerchantOrderItems($merchantOmsTriggerRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOmsTriggerResponseTransfer
     */
    public function triggerEventForMerchantOrderItem(
        MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
    ): MerchantOmsTriggerResponseTransfer {
        return $this->merchantOmsFacade->triggerEventForMerchantOrderItem($merchantOmsTriggerRequestTransfer);
    }
}
