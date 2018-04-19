<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class FileRemover implements FileRemoverInterface
{
    use DatabaseTransactionHandlerTrait;
    
    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface
     */
    protected $fileLoader;

    /**
     * @var \Spryker\Zed\FileManager\Business\Model\FileContentInterface
     */
    protected $fileContent;

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected $fileManagerQueryContainer;

    /**
     * @param \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface $fileLoader
     * @param \Spryker\Zed\FileManager\Business\Model\FileContentInterface $fileContent
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface $fileManagerQueryContainer
     */
    public function __construct(FileLoaderInterface $fileLoader, FileContentInterface $fileContent, FileManagerQueryContainerInterface $fileManagerQueryContainer)
    {
        $this->fileLoader = $fileLoader;
        $this->fileContent = $fileContent;
        $this->fileManagerQueryContainer = $fileManagerQueryContainer;
    }

    /**
     * @param int $idFileInfo
     *
     * @return bool
     */
    public function deleteFileInfo($idFileInfo)
    {
        $fileInfo = $this->fileLoader->getFileInfo($idFileInfo);

        if ($fileInfo == null) {
            return false;
        }

        $this->handleDatabaseTransaction(
            function () use ($fileInfo) {
                $this->fileContent->delete($fileInfo->getStorageFileName());
                $fileInfo->delete();
            },
            $this->fileManagerQueryContainer->getConnection()
        );

        return true;
    }

    /**
     * @param int $idFile
     *
     * @return bool
     */
    public function delete($idFile)
    {
        $file = $this->fileLoader->getFile($idFile);

        if ($file == null) {
            return false;
        }

        $this->handleDatabaseTransaction(
            function () use ($file) {
                foreach ($file->getSpyFileInfos() as $fileInfo) {
                    $this->fileContent->delete($fileInfo->getStorageFileName());
                    $fileInfo->delete();
                }

                $file->delete();
            },
            $this->fileManagerQueryContainer->getConnection()
        );

        return true;
    }
}
