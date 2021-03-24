<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOmsTriggerResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;

class MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeBridge implements
    MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeInterface
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
     * @phpstan-return array<int, array<\Generated\Shared\Transfer\StateMachineItemTransfer>>
     *
     * @param int[] $merchantOrderItemIds
     *
     * @return array
     */
    public function getMerchantOrderItemsStateHistory(array $merchantOrderItemIds): array
    {
        return $this->merchantOmsFacade->getMerchantOrderItemsStateHistory($merchantOrderItemIds);
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
