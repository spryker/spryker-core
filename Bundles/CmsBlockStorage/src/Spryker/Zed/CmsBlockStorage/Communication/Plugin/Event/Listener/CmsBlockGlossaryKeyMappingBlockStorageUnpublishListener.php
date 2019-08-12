<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Communication\CmsBlockStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockStorage\CmsBlockStorageConfig getConfig()
 */
class CmsBlockGlossaryKeyMappingBlockStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $cmsBlockIds = $this->findCmsBlockIds($eventTransfers);

        $this->getFacade()->unpublish($cmsBlockIds);
    }

    /**
     * @param array $eventTransfers
     *
     * @return array
     */
    public function findCmsBlockIds(array $eventTransfers)
    {
        return $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK);
    }
}
