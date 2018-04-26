<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Orm\Zed\FileManager\Persistence\SpyFile;
use Spryker\Zed\FileManager\FileManagerConfig;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class FileLoader implements FileLoaderInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\FileManager\FileManagerConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\FileManager\FileManagerConfig $config
     */
    public function __construct(FileManagerQueryContainerInterface $queryContainer, FileManagerConfig $config)
    {
        $this->queryContainer = $queryContainer;
        $this->config = $config;
    }

    /**
     * @param int $idFile
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFile
     */
    public function getFile($idFile)
    {
        return $this->queryContainer->queryFileById($idFile)->findOne();
    }

    /**
     * @param int $idFileDirectory
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectory
     */
    public function getFileDirectory($idFileDirectory)
    {
        return $this->queryContainer->queryFileDirectoryById($idFileDirectory)->findOne();
    }

    /**
     * @param int $idFile
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    public function getLatestFileInfoByFkFile($idFile)
    {
        return $this->queryContainer->queryFileInfoByIdFile($idFile)->findOne();
    }

    /**
     * @param int $idFileInfo
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    public function getFileInfo($idFileInfo)
    {
        return $this->queryContainer
            ->queryFileInfo($idFileInfo)
            ->findOne();
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     *
     * @return string
     */
    public function buildFilename(SpyFile $file)
    {
        $fileInfo = $file->getSpyFileInfos()->getFirst();
        $fileName = sprintf(
            '%u%s%s.%s',
            $file->getIdFile(),
            $this->config->getFileNameVersionDelimiter(),
            $fileInfo->getVersionName(),
            $fileInfo->getExtension()
        );

        if ($file->getFkFileDirectory()) {
            $fileName = $file->getFkFileDirectory() . DIRECTORY_SEPARATOR . $fileName;
        }

        return $fileName;
    }
}
