<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
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
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     *
     * @return void
     */
    public function triggerForNewMerchantOrderItems(MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer): void
    {
        $this->getFactory()->createMerchantOmsEventTrigger()->triggerForNewMerchantOrderItems($merchantOmsTriggerRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     *
     * @return void
     */
    public function triggerEventForMerchantOrderItems(MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer): void
    {
        $this->getFactory()->createMerchantOmsEventTrigger()->triggerEventForMerchantOrderItems($merchantOmsTriggerRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByCriteria(MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer): array
    {
        return $this->getFactory()
            ->createStateMachineItemReader()
            ->getStateMachineItemsByCriteria($merchantOrderItemCriteriaTransfer);
    }
}
