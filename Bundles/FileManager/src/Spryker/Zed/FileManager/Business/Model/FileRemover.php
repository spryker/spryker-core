<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

class FileRemover implements FileRemoverInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileFinderInterface
     */
    protected $fileFinder;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileContentInterface
     */
    protected $fileContent;

    /**
     * FileSaver constructor.
     *
     * @param \Spryker\Zed\FileManager\Business\Model\FileFinderInterface $fileFinder
     * @param \Spryker\Zed\FileManager\Business\Model\FileContentInterface $fileContent
     */
    public function __construct(FileFinderInterface $fileFinder, FileContentInterface $fileContent)
    {
        $this->fileFinder = $fileFinder;
        $this->fileContent = $fileContent;
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

        $this->fileContent->delete($fileInfo->getStorageFileName());
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
            $this->fileContent->delete($fileInfo->getStorageFileName());
            $fileInfo->delete();
        }

        $file->delete();

        return true;
    }
}
