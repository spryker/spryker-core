<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Spryker\Service\FileManager\FileManagerServiceInterface;

class FileRemover implements FileRemoverInterface
{
    /**
     * @var \Spryker\Service\FileManager\FileManagerServiceInterface
     */
    protected $fileManagerService;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileFinderInterface
     */
    protected $fileFinder;

    /**
     * FileSaver constructor.
     *
     * @param \Spryker\Zed\FileManager\Business\Model\FileFinderInterface $fileFinder
     * @param \Spryker\Service\FileManager\FileManagerServiceInterface $fileManagerService
     */
    public function __construct(FileFinderInterface $fileFinder, FileManagerServiceInterface $fileManagerService)
    {
        $this->fileManagerService = $fileManagerService;
        $this->fileFinder = $fileFinder;
    }

    /**
     * @param int $fileInfoId
     *
     * @return bool
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
     *
     * @return bool
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
     *
     * @return \Orm\Zed\Cms\Persistence\SpyFile
     */
    protected function getFile(int $fileId)
    {
        return $this->fileFinder->getFile($fileId);
    }
}
