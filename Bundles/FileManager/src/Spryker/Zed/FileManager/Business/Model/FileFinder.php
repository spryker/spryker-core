<?php

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Orm\Zed\Cms\Persistence\SpyFile;
use Orm\Zed\Cms\Persistence\SpyFileInfo;
use Spryker\Service\FileManager\FileManagerService;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer;

class FileFinder
{

    /**
     * @var FileManagerQueryContainer
     */
    protected $queryContainer;

    /**
     * @var FileManagerService
     */
    protected $fileManagerService;

    /**
     * @var FileVersion
     */
    protected $fileVersion;

    /**
     * FileSaver constructor.
     * @param FileManagerQueryContainer $queryContainer
     */
    public function __construct(FileManagerQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $fileId
     * @return SpyFile
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function getFile(int $fileId)
    {
        return $this->queryContainer->queryFileById($fileId)->findOne();
    }

    /**
     * @param $fileId
     * @return SpyFileInfo
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function getLatestFileInfoByFkFile($fileId)
    {
        return $this->queryContainer->queryLatestFileInfoByFkFile($fileId)->findOne();
    }

    /**
     * @param int $fileInfoId
     * @return SpyFileInfo
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function getFileInfo(int $fileInfoId)
    {
        return $this->queryContainer
            ->queryFileInfo($fileInfoId)
            ->findOne();
    }
    
}
