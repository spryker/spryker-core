<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication\EventTrigger;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOmsTriggerResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantSalesOrderFacadeInterface;

class MerchantOmsEventTrigger implements MerchantOmsEventTriggerInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantSalesOrderFacadeInterface
     */
    protected $merchantSalesOrderFacade;

    /**
     * @var \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface
     */
    protected $merchantOmsFacade;

    /**
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
     * @param \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface $merchantOmsFacade
     */
    public function __construct(
        MerchantOmsToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade,
        MerchantOmsFacadeInterface $merchantOmsFacade
    ) {
        $this->merchantSalesOrderFacade = $merchantSalesOrderFacade;
        $this->merchantOmsFacade = $merchantOmsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOmsTriggerResponseTransfer
     */
    public function triggerMerchantOmsEvent(
        MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
    ): MerchantOmsTriggerResponseTransfer {
        $merchantOmsTriggerRequestTransfer->requireMerchantOrderItemReference();
        $merchantOmsTriggerRequestTransfer->requireMerchantOmsEventName();

        $merchantOrderItemCriteriaTransfer = (new MerchantOrderItemCriteriaTransfer())
            ->setReference($merchantOmsTriggerRequestTransfer->getMerchantOrderItemReference());

        $merchantOmsTriggerResponseTransfer = new MerchantOmsTriggerResponseTransfer();

        $merchantOrderItemTransfer = $this->merchantSalesOrderFacade->findMerchantOrderItem($merchantOrderItemCriteriaTransfer);

        if (!$merchantOrderItemTransfer) {
            return $merchantOmsTriggerResponseTransfer->setIsSuccessful(false)
                ->setMessage(sprintf(
                    'Failed! Merchant order item with reference "%s" was not found.',
                    $merchantOmsTriggerRequestTransfer->getMerchantOrderItemReference()
                ));
        }

        $transitionedItemsCount = $this->merchantOmsFacade->triggerEventForMerchantOrderItems(
            (new MerchantOmsTriggerRequestTransfer())
                ->setMerchantOmsEventName($merchantOmsTriggerRequestTransfer->getMerchantOmsEventName())
                ->addMerchantOrderItem($merchantOrderItemTransfer)
        );

        if (!$transitionedItemsCount) {
            return $merchantOmsTriggerResponseTransfer->setIsSuccessful(false)
                ->setMessage(sprintf(
                    'Failed! Event "%s" was not successfully triggered for merchant order item with reference "%s".',
                    $merchantOmsTriggerRequestTransfer->getMerchantOmsEventName(),
                    $merchantOmsTriggerRequestTransfer->getMerchantOrderItemReference()
                ));
        }

        return $merchantOmsTriggerResponseTransfer->setIsSuccessful(true)->setMessage('Success.');
    }
}
