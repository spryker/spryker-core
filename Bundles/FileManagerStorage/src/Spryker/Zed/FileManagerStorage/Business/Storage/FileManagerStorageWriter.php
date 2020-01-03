<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\FileStorageDataTransfer;
use Generated\Shared\Transfer\FileStorageTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\FileManager\Exception\FileStorageNotFoundException;
use Spryker\Zed\FileManagerStorage\Dependency\Facade\FileManagerStorageToLocaleFacadeInterface;
use Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageEntityManagerInterface;
use Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class FileManagerStorageWriter implements FileManagerStorageWriterInterface
{
    use TransactionTrait;

    protected const KEY_DELIMITER = '_';

    /**
     * @var \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\FileManagerStorage\Dependency\Facade\FileManagerStorageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageEntityManagerInterface $entityManager
     * @param \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageRepositoryInterface $repository
     * @param \Spryker\Zed\FileManagerStorage\Dependency\Facade\FileManagerStorageToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        FileManagerStorageEntityManagerInterface $entityManager,
        FileManagerStorageRepositoryInterface $repository,
        FileManagerStorageToLocaleFacadeInterface $localeFacade
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int[] $fileIds
     *
     * @return bool
     */
    public function publish(array $fileIds)
    {
        $fileTransfers = $this->repository->findFilesByIds($fileIds);
        $fileStorageTransfers = $this->repository->findFileStoragesByIds($fileIds);

        return $this->getTransactionHandler()->handleTransaction(function () use ($fileTransfers, $fileStorageTransfers) {
            return $this->executePublishTransaction($fileTransfers, $fileStorageTransfers);
        });
    }

    /**
     * @param int[] $fileIds
     *
     * @return bool
     */
    public function unpublish(array $fileIds)
    {
        $fileStorageTransfers = $this->repository->findFileStoragesByIds($fileIds);

        return $this->getTransactionHandler()->handleTransaction(function () use ($fileStorageTransfers) {
            return $this->executeUnpublishTransaction($fileStorageTransfers);
        });
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\FileTransfer[] $fileTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\FileStorageTransfer[] $fileStorageTransfers
     *
     * @return bool
     */
    protected function executePublishTransaction(ArrayObject $fileTransfers, ArrayObject $fileStorageTransfers)
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();

        foreach ($availableLocales as $locale) {
            $this->storeDataSet($fileTransfers, $fileStorageTransfers, $locale);
        }

        return true;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\FileStorageTransfer[] $fileStorageTransfers
     *
     * @throws \Spryker\Zed\FileManager\Exception\FileStorageNotFoundException
     *
     * @return bool
     */
    protected function executeUnpublishTransaction(ArrayObject $fileStorageTransfers)
    {
        foreach ($fileStorageTransfers as $fileStorageTransfer) {
            if ($this->entityManager->deleteFileStorage($fileStorageTransfer) === false) {
                throw new FileStorageNotFoundException(sprintf('Target file storage entry with id %s not found', $fileStorageTransfer->getIdFileStorage()));
            }
        }

        return true;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\FileTransfer[] $fileTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\FileStorageTransfer[] $fileStorageTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function storeDataSet(ArrayObject $fileTransfers, ArrayObject $fileStorageTransfers, LocaleTransfer $localeTransfer)
    {
        foreach ($fileTransfers as $fileTransfer) {
            $key = $fileTransfer->getIdFile() . static::KEY_DELIMITER . $localeTransfer->getLocaleName();

            if (!$fileTransfer->getFileInfo()->count() && isset($fileStorageTransfers[$key])) {
                $this->unpublish([$fileTransfer->getIdFile()]);
                continue;
            }

            if (empty($fileStorageTransfers[$key])) {
                $this->createDataSet($fileTransfer, $localeTransfer);
                continue;
            }

            $this->updateDataSet($fileTransfer, $fileStorageTransfers[$key], $localeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function createDataSet(FileTransfer $fileTransfer, LocaleTransfer $localeTransfer)
    {
        $fileStorageTransfer = new FileStorageTransfer();
        $fileStorageTransfer->setFileName($fileTransfer->getFileName());
        $fileStorageTransfer->setLocale($localeTransfer->getLocaleName());
        $fileStorageTransfer->setFkFile($fileTransfer->getIdFile());
        $fileStorageTransfer->setData(
            $this->mapToFileStorageDataTransfer($fileTransfer, $localeTransfer)
        );

        $this->entityManager->saveFileStorage($fileStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param \Generated\Shared\Transfer\FileStorageTransfer $fileStorageTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function updateDataSet(FileTransfer $fileTransfer, FileStorageTransfer $fileStorageTransfer, LocaleTransfer $localeTransfer)
    {
        $fileStorageDataTransfer = $this->mapToFileStorageDataTransfer($fileTransfer, $localeTransfer);
        $fileStorageTransfer->setFileName($fileTransfer->getFileName());
        $fileStorageTransfer->setData($fileStorageDataTransfer);
        $this->entityManager->saveFileStorage($fileStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\FileStorageDataTransfer
     */
    protected function mapToFileStorageDataTransfer(FileTransfer $fileTransfer, LocaleTransfer $localeTransfer)
    {
        $fileInfoTransfers = $fileTransfer->getFileInfo();
        $localizedAttributes = $fileTransfer->getLocalizedAttributes();
        $latestFileInfo = $this->getLatestFileInfo($fileInfoTransfers);

        $fileStorageDataTransfer = new FileStorageDataTransfer();
        $fileStorageDataTransfer->fromArray($fileTransfer->toArray(), true);
        $fileStorageDataTransfer->setLocale($localeTransfer->getLocaleName());
        $fileStorageDataTransfer->setType($latestFileInfo->getType());
        $fileStorageDataTransfer->setVersion($latestFileInfo->getVersion());
        $fileStorageDataTransfer->setVersions($fileInfoTransfers);
        $fileStorageDataTransfer->setSize($latestFileInfo->getSize());
        $fileStorageDataTransfer->setStorageName($latestFileInfo->getStorageName());
        $fileStorageDataTransfer->setStorageFileName($latestFileInfo->getStorageFileName());
        $fileStorageDataTransfer->setFkFile($fileTransfer->getIdFile());
        $this->addLocalizedAttributesToFileStorageDataTransfer($fileStorageDataTransfer, $localizedAttributes, $localeTransfer);

        return $fileStorageDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileStorageDataTransfer $fileStorageTransfer
     * @param \ArrayObject $localizedAttributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function addLocalizedAttributesToFileStorageDataTransfer(FileStorageDataTransfer $fileStorageTransfer, ArrayObject $localizedAttributes, LocaleTransfer $localeTransfer)
    {
        if (empty($localizedAttributes[$localeTransfer->getIdLocale()])) {
            return;
        }

        $localizedAttributesForCurrentLocale = $localizedAttributes[$localeTransfer->getIdLocale()];
        $fileStorageTransfer->setAlt($localizedAttributesForCurrentLocale->getAlt());
        $fileStorageTransfer->setTitle($localizedAttributesForCurrentLocale->getTitle());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\FileInfoTransfer[] $fileInfoTransfers
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer|null
     */
    protected function getLatestFileInfo(ArrayObject $fileInfoTransfers)
    {
        return array_reduce($fileInfoTransfers->getArrayCopy(), function ($a, $b) {
            return $a ? ($a->getVersion() > $b->getVersion() ? $a : $b) : $b;
        });
    }
}
