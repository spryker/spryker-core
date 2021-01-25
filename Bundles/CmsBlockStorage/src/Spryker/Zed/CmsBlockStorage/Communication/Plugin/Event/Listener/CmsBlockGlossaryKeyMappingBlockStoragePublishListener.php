<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Communication\CmsBlockStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockStorage\CmsBlockStorageConfig getConfig()
 */
class CmsBlockGlossaryKeyMappingBlockStoragePublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $cmsBlockIds = $this->findCmsBlockIds($eventEntityTransfers);
        $this->getFacade()->publish($cmsBlockIds);
    }

    /**
     * @param array $eventTransfers
     *
     * @return array
     */
    public function findCmsBlockIds(array $eventTransfers): array
    {
        return $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK);
    }
}
