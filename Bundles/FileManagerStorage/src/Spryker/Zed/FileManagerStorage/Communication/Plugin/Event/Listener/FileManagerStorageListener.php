<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\FileStorageTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\FileManager\Dependency\FileManagerEvents;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\FileManagerStorage\Communication\FileManagerStorageCommunicationFactory getFactory()
 */
class FileManagerStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
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
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $fileIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);

        if ($eventName === FileManagerEvents::ENTITY_FILE_DELETE) {
            $this->unpublish($fileIds);
        }

        if ($eventName === FileManagerEvents::ENTITY_FILE_CREATE || $eventName === FileManagerEvents::ENTITY_FILE_UPDATE) {
            $this->publish($fileIds);
        }
    }

    /**
     * @param $fileIds
     *
     * @return void
     */
    protected function publish($fileIds)
    {
        $fileEntities = $this->findFileEntities($fileIds);
        $this->storeData($fileEntities);
    }

    /**
     * @return void
     */
    protected function storeData($fileEntities)
    {
        $availableLocales = $this->getFactory()->getLocaleFacade()->getAvailableLocales();

        foreach ($availableLocales as $locale) {
            $this->storeDataSet($fileEntities, $locale);
        }
    }

    /**
     * @return void
     */
    protected function storeDataSet($fileEntities, LocaleTransfer $locale)
    {
        foreach ($fileEntities as $fileEntity) {
            $fileStorageTransfer = $this->mapToFileStorageTransfer($fileEntity, $locale);
            $fileStorage = new SpyFileStorage();
            $fileStorage->setLocale($locale->getLocaleName());
            $fileStorage->setData($fileEntity->toArray());

            $fileStorage->save();
        }
    }

    protected function mapToFileStorageTransfer($fileEntity, LocaleTransfer $locale)
    {
        $fileStorageTransfer = new FileStorageTransfer();
        $fileStorageTransfer->fromArray($fileEntity->toArray());
        $fileStorageTransfer->setLocale($locale->getLocaleName());
        $fileStorageTransfer->setType($fileEntity->getFileInfo()->getType());

        return $fileStorageTransfer;
    }

    /**
     * @param $fileIds
     *
     * @return array
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    protected function findFileEntities($fileIds)
    {
        $files = $this->getQueryContainer()
            ->queryFilesByIds($fileIds)
            ->find();

        foreach ($files as $file) {
            $latestFileInfo = $file->getSpyFileInfos()->getFirst();
            $file->setVirtualColumn('fileInfo', $latestFileInfo);
        }

        return $files;
    }
}
