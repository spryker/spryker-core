<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class FileFinder implements FileFinderInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * FileSaver constructor.
     *
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer $queryContainer
     */
    public function __construct(FileManagerQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $fileId
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFile
     */
    public function getFile(int $fileId)
    {
        return $this->queryContainer->queryFileById($fileId)->findOne();
    }

    /**
     * @param int|null $fileId
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfo
     */
    public function getLatestFileInfoByFkFile(int $fileId = null)
    {
        return $this->queryContainer->queryLatestFileInfoByFkFile($fileId)->findOne();
    }

    /**
     * @param int $fileInfoId
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFileInfo
     */
    public function getFileInfo(int $fileInfoId)
    {
        return $this->queryContainer
            ->queryFileInfo($fileInfoId)
            ->findOne();
    }
}
