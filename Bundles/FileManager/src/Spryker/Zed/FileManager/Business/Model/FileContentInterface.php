<?php

namespace Spryker\Zed\FileManager\Business\Model;

interface FileContentInterface
{
    /**
     * @param string $currentFilePathName
     * @param string $fileName
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     */
    public function save(string $currentFilePathName, string $fileName);

    /**
     * @param string $fileName
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     */
    public function delete(string $fileName);

    /**
     * @param string $fileName
     * @return string
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     */
    public function read(string $fileName);
}