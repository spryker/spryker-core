<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\CmsBlock\Dependency\CmsBlockEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Communication\CmsBlockStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface getQueryContainer()
 */
class CmsBlockStorageListener extends AbstractCmsBlockStorageListener implements EventBulkHandlerInterface
{
    /**
     * @param array $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $cmsBlockIds = $this->getFactory()->getEventBehaviourFacade()->getEventTransferIds($eventTransfers);

        if ($eventName === CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_DELETE ||
            $eventName === CmsBlockEvents::CMS_BLOCK_UNPUBLISH
        ) {
            $this->unpublish($cmsBlockIds);

            return;
        }

        $this->publish($cmsBlockIds);
    }
}
