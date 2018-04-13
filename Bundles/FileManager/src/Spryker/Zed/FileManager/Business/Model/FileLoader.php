<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class FileLoader implements FileLoaderInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * FileSaver constructor.
     *
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $queryContainer
     */
    public function __construct(FileManagerQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
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
        return $this->queryContainer->queryFileInfoByFkFile($idFile)->findOne();
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
}
