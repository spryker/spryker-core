<?php

namespace Spryker\Service\FileManager;

interface FileManagerServiceInterface
{
    /**
     * @param string $fileName
     * @return string
     */
    public function getPublicUrl($fileName);

    /**
     * @param string $fileName
     * @return string
     */
    public function getZedUrl($fileName);

    /**
     * @param string $fileName
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function read($fileName);
}
