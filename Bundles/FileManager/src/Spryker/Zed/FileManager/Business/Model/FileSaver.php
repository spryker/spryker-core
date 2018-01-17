<?php

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Orm\Zed\Cms\Persistence\SpyFile;
use Orm\Zed\Cms\Persistence\SpyFileInfo;
use Spryker\Service\FileManager\FileManagerService;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer;

class FileSaver
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
     * @param FileManagerQueryContainer $queryContainer
     * @param FileVersion $fileVersion
     * @param FileFinder $fileFinder
     * @param FileManagerService $fileManagerService
     */
    public function __construct(
        FileManagerQueryContainer $queryContainer,
        FileVersion $fileVersion,
        FileFinder $fileFinder,
        FileManagerService $fileManagerService
    ) {
        $this->queryContainer = $queryContainer;
        $this->fileManagerService = $fileManagerService;
        $this->fileVersion = $fileVersion;
        $this->fileFinder = $fileFinder;
    }

    /**
     * @param FileManagerSaveRequestTransfer $saveRequestTransfer
     * @return int
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function save(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        if ($this->checkFileExists($saveRequestTransfer)) {
            return $this->update($saveRequestTransfer);
        }

        return $this->create($saveRequestTransfer);
    }

    /**
     * @param FileManagerSaveRequestTransfer $saveRequestTransfer
     * @return int
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    protected function update(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        $file = $this->fileFinder->getFile($saveRequestTransfer->getFile()->getIdFile());

        return $this->saveFile($file, $saveRequestTransfer);
    }

    /**
     * @param FileManagerSaveRequestTransfer $saveRequestTransfer
     * @return int
     */
    protected function create(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        $file = new SpyFile();

        return $this->saveFile($file, $saveRequestTransfer);
    }

    protected function saveFile(SpyFile $file, FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        $this->queryContainer->getConnection()->beginTransaction();

        try {
            $file->fromArray($saveRequestTransfer->getFile()->toArray());
            $fileInfo = $this->createFileInfo($saveRequestTransfer->getFileInfo());
            $file->addSpyFileInfo($fileInfo);

            $savedRowsCount = $file->save();

            $contentId = $this->fileManagerService
                ->save($saveRequestTransfer->getTempFilePath());
            $this->addContentId($fileInfo, $contentId);

            $this->queryContainer->getConnection()->commit();

            return $savedRowsCount;
        } catch (\Exception $exception) {
            $this->queryContainer->getConnection()->rollBack();
        }
    }

    /**
     * @param SpyFileInfo $fileInfo
     * @param string $contentId
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function addContentId(SpyFileInfo $fileInfo, string $contentId)
    {
        $fileInfo->reload();
        $fileInfo->setContentId($contentId);

        $fileInfo->save();
    }

    /**
     * @param FileInfoTransfer $fileInfoTransfer
     * @return SpyFileInfo
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    protected function createFileInfo(FileInfoTransfer $fileInfoTransfer)
    {
        $fileInfo = new SpyFileInfo();
        $fileInfo->fromArray($fileInfoTransfer->toArray());

        $newVersion = $this->fileVersion->getNewVersionNumber($fileInfoTransfer->getFkFile());
        $newVersionName = $this->fileVersion->getNewVersionName($newVersion);
        $fileInfo->setVersion($newVersion);
        $fileInfo->setVersionName($newVersionName);

        return $fileInfo;
    }

    /**
     * @param FileManagerSaveRequestTransfer $saveRequestTransfer
     * @return bool
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    protected function checkFileExists(FileManagerSaveRequestTransfer $saveRequestTransfer)
    {
        $fileId = $saveRequestTransfer->getFile()->getIdFile();

        if ($fileId == null) {
            return false;
        }

        $file = $this->queryContainer
            ->queryFileById($fileId)
            ->findOne();

        return $file !== null;
    }
    
}
