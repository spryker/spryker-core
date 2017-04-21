<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business\Model;

use Spryker\Zed\FileSystem\Dependency\Facade\FileSystemToFlysystemInterface;

//TODO replace parameters with transfer
class FileSystemHandler implements FileSystemHandlerInterface
{

    /**
     * @var \Spryker\Zed\FileSystem\Dependency\Facade\FileSystemToFlysystemInterface
     */
    protected $flysystemService;

    /**
     * @param \Spryker\Zed\FileSystem\Dependency\Facade\FileSystemToFlysystemInterface $flysystemService
     */
    public function __construct(
        FileSystemToFlysystemInterface $flysystemService
    ) {
        $this->flysystemService = $flysystemService;
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return bool
     */
    public function has($fileSystemName, $path)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->has($path);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false The file contents or false on failure.
     */
    public function read($fileSystemName, $path)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->read($path);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool True on success, false on failure.
     */
    public function put($fileSystemName, $path, $content, array $config = [])
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->put($path, $content, $config);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool True on success, false on failure.
     */
    public function update($fileSystemName, $path, $content, array $config = [])
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->update($path, $content, $config);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool True on success, false on failure.
     */
    public function write($fileSystemName, $path, $content, array $config = [])
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->write($path, $content, $config);
    }

}
