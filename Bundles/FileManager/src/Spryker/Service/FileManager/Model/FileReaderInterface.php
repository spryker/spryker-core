<?php

namespace Spryker\Service\FileManager\Model;

use Generated\Shared\Transfer\FileManagerReadResponseTransfer;

interface FileReaderInterface
{
    /**
     * @param $fileName
     * @return FileManagerReadResponseTransfer
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     */
    public function read($fileName);
}