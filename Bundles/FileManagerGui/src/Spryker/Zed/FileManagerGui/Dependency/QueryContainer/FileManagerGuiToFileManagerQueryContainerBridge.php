<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Dependency\QueryContainer;

class FileManagerGuiToFileManagerQueryContainerBridge implements FileManagerGuiToFileManagerQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $queryContainer
     */
    public function __construct($queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idFile
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFileWithFileInfoById(int $idFile)
    {
        return $this->queryContainer->queryFileWithFileInfoById($idFile);
    }

    /**
     * @param int $idFile
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFileById(int $idFile)
    {
        return $this->queryContainer->queryFileById($idFile);
    }

    /**
     * @param int $idFileDirectory
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryQuery
     */
    public function queryFileDirectoryById(int $idFileDirectory)
    {
        return $this->queryContainer->queryFileDirectoryById($idFileDirectory);
    }

    /**
     * @param int|null $idFile
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfoByIdFile(int $idFile = null)
    {
        return $this->queryContainer->queryFileInfoByIdFile($idFile);
    }

    /**
     * @param int $idFileInfo
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfo(int $idFileInfo)
    {
        return $this->queryContainer->queryFileInfo($idFileInfo);
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function queryFiles()
    {
        return $this->queryContainer->queryFiles();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyMimeTypeQuery
     */
    public function queryMimeType()
    {
        return $this->queryContainer->queryMimeType();
    }
}
