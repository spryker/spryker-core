<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\FileManager\Persistence\Map\SpyFileLocalizedAttributesTableMap;
use Spryker\Zed\FileManager\Dependency\FileManagerEvents;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\FileManagerStorage\Communication\FileManagerStorageCommunicationFactory getFactory()
 */
class FileLocalizedAttributesListener extends AbstractFileManagerListener
{
    use DatabaseTransactionHandlerTrait;

    /**
     * Specification
     *  - Listeners needs to implement this interface to execute the codes for more
     *  than one event at same time (Bulk Operation)
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $fileIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyFileLocalizedAttributesTableMap::COL_FK_FILE);

        if ($eventName === FileManagerEvents::ENTITY_FILE_LOCALIZED_ATTRIBUTES_CREATE
            || $eventName === FileManagerEvents::ENTITY_FILE_LOCALIZED_ATTRIBUTES_UPDATE
            || $eventName === FileManagerEvents::ENTITY_FILE_LOCALIZED_ATTRIBUTES_DELETE
        ) {
            $this->publish($fileIds);
        }
    }
}
