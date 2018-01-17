<?php

namespace Spryker\Zed\FileManager\Business\Model;

use Orm\Zed\Cms\Persistence\SpyFile;
use Spryker\Service\FileManager\FileManagerService;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer;

class FileRemover
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
     * @var FileFinder
     */
    private $fileFinder;

    /**
     * FileSaver constructor.
     * @param FileFinder $fileFinder
     * @param FileManagerService $fileManagerService
     */
    public function __construct(FileFinder $fileFinder, FileManagerService $fileManagerService)
    {
        $this->fileManagerService = $fileManagerService;
        $this->fileFinder = $fileFinder;
    }

    /**
     * @param int $fileInfoId
     * @return bool
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function deleteFileInfo(int $fileInfoId)
    {
        $fileInfo = $this->fileFinder->getFileInfo($fileInfoId);

        if ($fileInfo == null) {
            return false;
        }

        $this->fileManagerService->delete($fileInfo->getContentId());
        $fileInfo->delete();

        return true;
    }

    /**
     * @param int $fileId
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function delete(int $fileId)
    {
        $file = $this->fileFinder->getFile($fileId);

        if ($file == null) {
            return false;
        }

        foreach ($file->getSpyFileInfos() as $fileInfo) {
            $this->fileManagerService->delete($fileInfo->getContentId());
            $fileInfo->delete();
        }

        return true;
    }

    /**
     * @param int $fileId
     * @return SpyFile
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    protected function getFile(int $fileId)
    {
        return $this->queryContainer->queryFileById($fileId)->findOne();
    }

}
