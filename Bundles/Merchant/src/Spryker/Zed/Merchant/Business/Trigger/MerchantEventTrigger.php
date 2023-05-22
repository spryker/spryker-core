<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Trigger;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;

class MerchantEventTrigger implements MerchantEventTriggerInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface
     */
    protected MerchantToEventFacadeInterface $eventFacade;

    /**
     * @param \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface $eventFacade
     */
    public function __construct(MerchantToEventFacadeInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function triggerMerchantCreatedEvent(MerchantTransfer $merchantTransfer): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($merchantTransfer->getIdMerchant());

        $this->eventFacade->trigger(MerchantEvents::MERCHANT_CREATED, $eventEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function triggerMerchantUpdatedEvent(MerchantTransfer $merchantTransfer): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($merchantTransfer->getIdMerchant());

        $this->eventFacade->trigger(MerchantEvents::MERCHANT_UPDATED, $eventEntityTransfer);
    }
}
