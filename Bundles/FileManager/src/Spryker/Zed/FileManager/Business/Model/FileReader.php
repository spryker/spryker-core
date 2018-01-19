<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

class FileReader implements FileReaderInterface
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
     * @param \Spryker\Zed\FileManager\Business\Model\FileFinderInterface $fileFinder
     * @param \Spryker\Zed\FileManager\Business\Model\FileContentInterface $fileContent
     */
    public function __construct(FileFinderInterface $fileFinder, FileContentInterface $fileContent)
    {
        $this->fileFinder = $fileFinder;
        $this->fileContent = $fileContent;
    }

    /**
     * @param int $fileId
     *
     * @return bool
     */
    public function read(int $fileId)
    {
        $fileInfo = $this->fileFinder->getLatestFileInfoByFkFile($fileId);

        if ($fileInfo == null) {
            return false;
        }

        return $this->fileContent->read($fileInfo->getStorageFileName());
    }
}
