<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileDirectoryTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

class FileDirectorySaver implements FileDirectorySaverInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $queryContainer
     */
    public function __construct(
        FileManagerQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    public function save(FileDirectoryTransfer $fileDirectoryTransfer)
    {
//        if ($this->checkFileExists($fileDirectoryTransfer)) {
//            return $this->update($fileDirectoryTransfer);
//        }

        return $this->create($fileDirectoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    protected function update(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        // $file = $this->fileFinder->getFile($saveRequestTransfer->getFile()->getIdFile());

        // return $this->saveFileDirectory($fileDirectoryTransfer, $saveRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    protected function create(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $fileDirectory = new SpyFileDirectory();

        return $this->saveFileDirectory($fileDirectory, $fileDirectoryTransfer);
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return int
     */
    protected function saveFileDirectory(SpyFileDirectory $fileDirectory, FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $fileDirectory->fromArray($fileDirectoryTransfer->toArray());
    }
}
