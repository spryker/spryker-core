<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\CmsStorage\Communication\CmsStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface getQueryContainer()
 */
class CmsPageUrlStorageListener extends AbstractCmsPageStorageListener implements EventBulkHandlerInterface
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
        $cmsPageIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyUrlTableMap::COL_FK_RESOURCE_PAGE);

        if (empty($cmsPageIds)) {
            return;
        }

        if ($eventName === UrlEvents::ENTITY_SPY_URL_DELETE) {
            $this->unpublish($cmsPageIds);
        } else {
            $this->publish($cmsPageIds);
        }
    }
}
