<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsSlotStorage\Communication\CmsSlotStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotStorage\CmsSlotStorageConfig getConfig()
 * @method \Spryker\Zed\CmsSlotStorage\Business\CmsSlotStorageFacadeInterface getFacade()
 */
class CmsSlotStoragePublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $cmsSlotIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventTransfers);

        $this->getFacade()->publishCmsSlots($cmsSlotIds);
    }
}
