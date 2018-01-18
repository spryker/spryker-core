<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Orm\Zed\Cms\Persistence\SpyFileInfo;
use Spryker\Zed\FileManager\Exception\FileInfoNotFoundException;
use Spryker\Zed\FileManager\Exception\FileNotFoundException;

class FileRollback implements FileRollbackInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileVersionInterface
     */
    protected $fileVersion;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileFinderInterface
     */
    protected $fileFinder;

    /**
     * FileSaver constructor.
     *
     * @param \Spryker\Zed\FileManager\Business\Model\FileFinderInterface $fileFinder
     * @param \Spryker\Zed\FileManager\Business\Model\FileVersionInterface $fileVersion
     */
    public function __construct(FileFinderInterface $fileFinder, FileVersionInterface $fileVersion)
    {
        $this->fileVersion = $fileVersion;
        $this->fileFinder = $fileFinder;
    }

    /**
     * @param int $fileId
     * @param int $fileInfoId
     *
     * @return void
     */
    public function rollback(int $fileId, int $fileInfoId)
    {
        $file = $this->getFile($fileId);
        $targetFileInfo = $this->getFileInfo($fileInfoId);
        $file->addSpyFileInfo($this->createNewFileInfo($targetFileInfo));
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyFileInfo $targetFileInfo
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfo
     */
    protected function createNewFileInfo(SpyFileInfo $targetFileInfo)
    {
        $fileInfo = new SpyFileInfo();
        $fileInfo->fromArray($targetFileInfo->toArray());

        $this->updateVersion($fileInfo, $targetFileInfo->getFkFile());
        $fileInfo->save();

        return $fileInfo;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyFileInfo $fileInfo
     * @param int $fileId
     *
     * @return void
     */
    protected function updateVersion(SpyFileInfo $fileInfo, int $fileId)
    {
        $newVersion = $this->fileVersion->getNewVersionNumber($fileId);
        $newVersionName = $this->fileVersion->getNewVersionName($newVersion);
        $fileInfo->setVersion($newVersion);
        $fileInfo->setVersionName($newVersionName);
    }

    /**
     * @param int $fileId
     *
     * @throws \Spryker\Zed\FileManager\Exception\FileNotFoundException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFile
     */
    protected function getFile(int $fileId)
    {
        $file = $this->fileFinder->getFile($fileId);

        if ($file == null) {
            throw new FileNotFoundException(sprintf('File with id %s not found', $fileId));
        }

        return $file;
    }

    /**
     * @param int $fileInfoId
     *
     * @throws \Spryker\Zed\FileManager\Exception\FileInfoNotFoundException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfo
     */
    protected function getFileInfo(int $fileInfoId)
    {
        $fileInfo = $this->fileFinder->getFileInfo($fileInfoId);

        if ($fileInfo == null) {
            throw new FileInfoNotFoundException(sprintf('Target file info with id %s not found', $fileInfoId));
        }

        return $fileInfo;
    }
}
