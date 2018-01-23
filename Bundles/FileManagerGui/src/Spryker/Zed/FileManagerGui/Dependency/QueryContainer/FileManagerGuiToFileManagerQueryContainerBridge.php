<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Dependency\QueryContainer;

class FileManagerGuiToFileManagerQueryContainerBridge implements FileManagerGuiToFileManagerQueryContainerBridgeInterface
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
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function queryFileWithFileInfoById(int $id)
    {
        return $this->queryContainer->queryFileWithFileInfoById($id);
    }

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function queryFileById(int $id)
    {
        return $this->queryContainer->queryFileById($id);
    }

    /**
     * @param int|null $idFile
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfoByFkFile(int $idFile = null)
    {
        return $this->queryContainer->queryFileInfoByFkFile($idFile);
    }

    /**
     * @param int $idFileInfo
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfoQuery
     */
    public function queryFileInfo(int $idFileInfo)
    {
        return $this->queryContainer->queryFileInfo($idFileInfo);
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyFileQuery
     */
    public function queryFiles()
    {
        return $this->queryContainer->queryFiles();
    }
}
