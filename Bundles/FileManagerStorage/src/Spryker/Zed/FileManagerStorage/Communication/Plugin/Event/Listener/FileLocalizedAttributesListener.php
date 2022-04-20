<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\FileManager\Persistence\Map\SpyFileLocalizedAttributesTableMap;
use Spryker\Zed\FileManager\Dependency\FileManagerEvents;

/**
 * @method \Spryker\Zed\FileManagerStorage\Communication\FileManagerStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileManagerStorage\FileManagerStorageConfig getConfig()
 * @method \Spryker\Zed\FileManagerStorage\Business\FileManagerStorageFacadeInterface getFacade()
 */
class FileLocalizedAttributesListener extends AbstractFileManagerListener
{
    /**
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $fileIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventEntityTransfers, SpyFileLocalizedAttributesTableMap::COL_FK_FILE);

        if (
            $eventName === FileManagerEvents::ENTITY_FILE_LOCALIZED_ATTRIBUTES_CREATE
            || $eventName === FileManagerEvents::ENTITY_FILE_LOCALIZED_ATTRIBUTES_UPDATE
            || $eventName === FileManagerEvents::ENTITY_FILE_LOCALIZED_ATTRIBUTES_DELETE
        ) {
            $this->publish($fileIds);
        }
    }
}
