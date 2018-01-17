<?php

namespace Spryker\Zed\FileManager\Business\Model;

use Orm\Zed\Cms\Persistence\SpyFile;
use Orm\Zed\Cms\Persistence\SpyFileInfo;
use Spryker\Zed\FileManager\Exception\FileInfoNotFoundException;
use Spryker\Zed\FileManager\Exception\FileNotFoundException;

class FileRollback
{

    /**
     * @var FileVersion
     */
    protected $fileVersion;

    /**
     * @var FileFinder
     */
    protected $fileFinder;

    /**
     * FileSaver constructor.
     * @param FileFinder $fileFinder
     * @param FileVersion $fileVersion
     */
    public function __construct(FileFinder $fileFinder, FileVersion $fileVersion)
    {
        $this->fileVersion = $fileVersion;
        $this->fileFinder = $fileFinder;
    }

    /**
     * @param int $fileId
     * @param int $fileInfoId
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     * @throws FileNotFoundException
     * @throws FileInfoNotFoundException
     */
    public function rollback(int $fileId, int $fileInfoId)
    {
        $file = $this->getFile($fileId);
        $targetFileInfo = $this->getFileInfo($fileInfoId);
        $file->addSpyFileInfo($this->createNewFileInfo($targetFileInfo));
    }

    /**
     * @param SpyFileInfo $targetFileInfo
     * @return SpyFileInfo
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
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
     * @param SpyFileInfo $fileInfo
     * @param int $fileId
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    protected function updateVersion(SpyFileInfo $fileInfo, int $fileId)
    {
        $newVersion = $this->fileVersion->getNewVersionNumber($fileId);
        $newVersionName = $this->fileVersion->getNewVersionName($newVersion);
        $fileInfo->setVersion($newVersion);
        $fileInfo->setVersionName($newVersionName);
    }

    /**
     * @param $fileId
     * @return SpyFile
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     * @throws FileNotFoundException
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
     * @return SpyFileInfo
     * @throws FileInfoNotFoundException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
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
