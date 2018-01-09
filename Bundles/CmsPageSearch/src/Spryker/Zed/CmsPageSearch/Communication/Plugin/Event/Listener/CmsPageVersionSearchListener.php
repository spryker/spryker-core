<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\Cms\Persistence\Map\SpyCmsVersionTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

class CmsPageVersionSearchListener extends AbstractCmsPageSearchListener implements EventBulkHandlerInterface
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
        $cmsPageIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyCmsVersionTableMap::COL_FK_CMS_PAGE);

        $this->publish($cmsPageIds);
    }
}
