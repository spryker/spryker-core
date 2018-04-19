<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Spryker\Zed\FileManager\Exception\FileInfoNotFoundException;
use Spryker\Zed\FileManager\Exception\FileNotFoundException;

class FileRollback implements FileRollbackInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileVersionInterface
     */
    protected $fileVersion;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface
     */
    protected $fileLoader;

    /**
     * @param \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface $fileLoader
     * @param \Spryker\Zed\FileManager\Business\Model\FileVersionInterface $fileVersion
     */
    public function __construct(FileLoaderInterface $fileLoader, FileVersionInterface $fileVersion)
    {
        $this->fileVersion = $fileVersion;
        $this->fileLoader = $fileLoader;
    }

    /**
     * @param int $idFile
     * @param int $idFileInfo
     *
     * @return void
     */
    public function rollback($idFile, $idFileInfo)
    {
        $file = $this->getFile($idFile);
        $targetFileInfo = $this->getFileInfo($idFileInfo);
        $file->addSpyFileInfo($this->createFileInfo($targetFileInfo));
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $targetFileInfo
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    protected function createFileInfo(SpyFileInfo $targetFileInfo)
    {
        $fileInfo = new SpyFileInfo();
        $targetFileInfoArray = $targetFileInfo->toArray();
        unset($targetFileInfoArray['id_file_info']);
        $fileInfo->fromArray($targetFileInfoArray);

        $this->updateVersion($fileInfo, $targetFileInfo->getFkFile());
        $fileInfo->save();

        return $fileInfo;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfo $fileInfo
     * @param int $idFile
     *
     * @return void
     */
    protected function updateVersion(SpyFileInfo $fileInfo, $idFile)
    {
        $nextVersion = $this->fileVersion->getNextVersionNumber($idFile);
        $newVersionName = $this->fileVersion->getNextVersionName($nextVersion);
        $fileInfo->setVersion($nextVersion);
        $fileInfo->setVersionName($newVersionName);
    }

    /**
     * @param int $idFile
     *
     * @throws \Spryker\Zed\FileManager\Exception\FileNotFoundException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFile
     */
    protected function getFile($idFile)
    {
        $file = $this->fileLoader->getFile($idFile);

        if ($file === null) {
            throw new FileNotFoundException(sprintf('File with id %s not found', $idFile));
        }

        return $file;
    }

    /**
     * @param int $idFileInfo
     *
     * @throws \Spryker\Zed\FileManager\Exception\FileInfoNotFoundException
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    protected function getFileInfo($idFileInfo)
    {
        $fileInfo = $this->fileLoader->getFileInfo($idFileInfo);

        if ($fileInfo === null) {
            throw new FileInfoNotFoundException(sprintf('Target file info with id %s not found', $idFileInfo));
        }

        return $fileInfo;
    }
}
