<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileDirectoryTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class FileDirectorySaver implements FileDirectorySaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileFinderInterface
     */
    protected $fileFinder;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileDirectoryLocalizedAttributesSaverInterface
     */
    protected $attributesSaver;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\FileManager\Business\Model\FileFinderInterface $fileFinder
     * @param \Spryker\Zed\FileManager\Business\Model\FileDirectoryLocalizedAttributesSaverInterface $attributesSaver
     */
    public function __construct(
        FileManagerQueryContainerInterface $queryContainer,
        FileFinderInterface $fileFinder,
        FileDirectoryLocalizedAttributesSaverInterface $attributesSaver
    ) {
        $this->queryContainer = $queryContainer;
        $this->fileFinder = $fileFinder;
        $this->attributesSaver = $attributesSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    public function save(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        if ($this->checkFileDirectoryExists($fileDirectoryTransfer)) {
            return $this->update($fileDirectoryTransfer);
        }

        return $this->create($fileDirectoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return bool
     */
    protected function checkFileDirectoryExists(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $fileDirectoryId = $fileDirectoryTransfer->getIdFileDirectory();

        if ($fileDirectoryId == null) {
            return false;
        }

        $file = $this->fileFinder->getFileDirectory($fileDirectoryId);

        return $file !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    protected function update(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $fileDirectory = $this->fileFinder->getFileDirectory($fileDirectoryTransfer->getIdFileDirectory());

        return $this->saveFileDirectory($fileDirectory, $fileDirectoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    protected function create(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $fileDirectory = new SpyFileDirectory();

        return $this->saveFileDirectory($fileDirectory, $fileDirectoryTransfer);
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    protected function saveFileDirectory(SpyFileDirectory $fileDirectory, FileDirectoryTransfer $fileDirectoryTransfer)
    {
        return $this->handleDatabaseTransaction(function () use ($fileDirectory, $fileDirectoryTransfer) {
            $fileDirectory->fromArray($fileDirectoryTransfer->toArray());

            $savedRowsCount = $fileDirectory->save();
            $this->attributesSaver->saveFileLocalizedAttributes($fileDirectory, $fileDirectoryTransfer);

            return $savedRowsCount;
        }, $this->queryContainer->getConnection());
    }

}
