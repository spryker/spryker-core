<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Spryker\Zed\CmsBlock\Dependency\CmsBlockEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

class CmsBlockGlossaryKeyMappingBlockStorageListener extends AbstractCmsBlockStorageListener implements EventBulkHandlerInterface
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
        $cmsBlockIds = $this->findCmsBlockIds($eventTransfers);

        if ($eventName === CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_DELETE) {
            $this->unpublish($cmsBlockIds);

            return;
        }

        $this->publish($cmsBlockIds);
    }

    /**
     * @param array $eventTransfers
     *
     * @return array
     */
    public function findCmsBlockIds(array $eventTransfers)
    {
        return $this->getFactory()->getEventBehaviourFacade()->getEventTransferForeignKeys($eventTransfers, SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK);
    }
}
