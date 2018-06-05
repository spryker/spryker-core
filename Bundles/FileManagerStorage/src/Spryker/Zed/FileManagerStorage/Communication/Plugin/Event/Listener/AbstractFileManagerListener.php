<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerStorageTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage;
use Spryker\Shared\FileManagerStorage\FileManagerStorageConstants;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\FileManagerStorage\Communication\FileManagerStorageCommunicationFactory getFactory()
 */
abstract class AbstractFileManagerListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    const FK_FILE = 'fkFile';
    const FK_LOCALE = 'fkLocale';
    const KEY_DELIMITER = '_';

    /**
     * @param array $fileIds
     *
     * @return void
     */
    protected function unpublish($fileIds)
    {
        $fileStorageEntities = $this->findFileStorageEntities($fileIds);

        foreach ($fileStorageEntities as $fileStorageEntity) {
            $fileStorageEntity->delete();
        }
    }

    /**
     * @param array $fileIds
     *
     * @return void
     */
    protected function publish($fileIds)
    {
        $fileEntities = $this->findFileEntities($fileIds);
        $fileStorageEntities = $this->findFileStorageEntities($fileIds);

        $this->storeData($fileEntities, $fileStorageEntities);
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile[] $fileEntities
     * @param \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage[] $fileStorageEntities
     *
     * @return void
     */
    protected function storeData($fileEntities, $fileStorageEntities)
    {
        $availableLocales = $this->getFactory()->getLocaleFacade()->getLocaleCollection();

        foreach ($availableLocales as $locale) {
            $this->storeDataSet($fileEntities, $fileStorageEntities, $locale);
        }
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile[] $fileEntities
     * @param \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage[] $fileStorageEntities
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function storeDataSet($fileEntities, $fileStorageEntities, LocaleTransfer $localeTransfer)
    {
        foreach ($fileEntities as $fileEntity) {
            $key = $fileEntity->getIdFile() . static::KEY_DELIMITER . $localeTransfer->getLocaleName();

            if (empty($fileStorageEntities[$key])) {
                $this->createDataSet($fileEntity, $localeTransfer);
                continue;
            }
            $fileStorageEntity = $fileStorageEntities[$key];

            $this->updateDataSet($fileEntity, $fileStorageEntity, $localeTransfer);
        }
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     * @param \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage $fileStorageEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function updateDataSet(SpyFile $fileEntity, SpyFileStorage $fileStorageEntity, LocaleTransfer $localeTransfer)
    {
        if ($fileStorageEntity->getFileName() !== $fileEntity->getFileName()) {
            $fileStorageEntity->delete();
            $this->createDataSet($fileEntity, $localeTransfer);
            return;
        }
        $fileStorageTransfer = $this->mapToFileManagerStorageTransfer($fileEntity, $localeTransfer);

        $fileStorageEntity->setData($fileStorageTransfer->toArray());
        $fileStorageEntity->save();
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    protected function createDataSet(SpyFile $fileEntity, LocaleTransfer $locale)
    {
        $fileStorageTransfer = $this->mapToFileManagerStorageTransfer($fileEntity, $locale);
        $fileStorage = new SpyFileStorage();
        $fileStorage->setLocale($locale->getLocaleName());
        $fileStorage->setFileName($fileStorageTransfer->getFileName());
        $fileStorage->setData($fileStorageTransfer->toArray());
        $fileStorage->setStore($this->getFactory()->getStore()->getStoreName());
        $fileStorage->setFkFile($fileStorageTransfer->getFkFile());

        $fileStorage->save();
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\FileManagerStorageTransfer
     */
    protected function mapToFileManagerStorageTransfer(SpyFile $fileEntity, LocaleTransfer $locale)
    {
        $latestFileInfo = $fileEntity->getSpyFileInfos()->getLast();
        $localizedAttributes = $fileEntity->getSpyFileLocalizedAttributess()->toKeyIndex(static::FK_LOCALE);

        $fileStorageTransfer = new FileManagerStorageTransfer();
        $fileStorageTransfer->fromArray($fileEntity->toArray(), true);
        $fileStorageTransfer->setLocale($locale->getLocaleName());
        $fileStorageTransfer->setType($latestFileInfo->getType());
        $fileStorageTransfer->setVersion($latestFileInfo->getVersion());
        $fileStorageTransfer->setVersions($this->getFileVersions($fileEntity));
        $fileStorageTransfer->setSize($latestFileInfo->getSize());
        $fileStorageTransfer->setStorageName($latestFileInfo->getStorageName());
        $fileStorageTransfer->setStorageFileName($latestFileInfo->getStorageFileName());
        $fileStorageTransfer->setFkFile($fileEntity->getIdFile());

        $this->mapLocalizedAttributes($fileStorageTransfer, $localizedAttributes, $locale);

        return $fileStorageTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     *
     * @return \ArrayObject
     */
    protected function getFileVersions(SpyFile $fileEntity)
    {
        $fileInfoTransfers = new ArrayObject();

        $fileVersions = $fileEntity->getSpyFileInfos();
        foreach ($fileVersions as $fileVersion) {
            $fileInfoTransfer = new FileInfoTransfer();
            $fileInfoTransfer->fromArray($fileVersion->toArray(), true);
            $fileInfoTransfers->append($fileInfoTransfer);
        }

        return $fileInfoTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerStorageTransfer $fileStorageTransfer
     * @param array $localizedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function mapLocalizedAttributes(FileManagerStorageTransfer $fileStorageTransfer, $localizedAttributes, LocaleTransfer $localeTransfer)
    {
        if (empty($localizedAttributes[$localeTransfer->getIdLocale()])) {
            return;
        }
        $localizedAttributesForCurrentLocale = $localizedAttributes[$localeTransfer->getIdLocale()];

        $fileStorageTransfer->setAlt($localizedAttributesForCurrentLocale->getAlt());
        $fileStorageTransfer->setTitle($localizedAttributesForCurrentLocale->getTitle());
    }

    /**
     * @param array $fileIds
     *
     * @return array
     */
    protected function findFileEntities($fileIds)
    {
        $files = $this->getQueryContainer()
            ->queryFilesByIds($fileIds)
            ->find();

        return $files;
    }

    /**
     * @param array $fileIds
     *
     * @return \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findFileStorageEntities($fileIds)
    {
        return $this->getQueryContainer()
            ->queryFileStorageByIds($fileIds)
            ->find()->toKeyIndex(FileManagerStorageConstants::STORAGE_COMPOSITE_KEY);
    }
}
