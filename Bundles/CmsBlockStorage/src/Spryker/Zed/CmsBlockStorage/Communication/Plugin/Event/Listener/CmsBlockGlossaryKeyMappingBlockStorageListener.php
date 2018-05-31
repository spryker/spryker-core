<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Spryker\Zed\CmsBlock\Dependency\CmsBlockEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Communication\CmsBlockStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacadeInterface getFacade()
 */
class CmsBlockGlossaryKeyMappingBlockStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

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
            $this->getFacade()->unpublish($cmsBlockIds);

            return;
        }

        $this->getFacade()->publish($cmsBlockIds);
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
