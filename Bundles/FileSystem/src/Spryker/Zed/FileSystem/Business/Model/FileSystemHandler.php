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
     * @return string|false
     */
    public function getMimeType($fileSystemName, $path)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->getMimetype($path);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getTimestamp($fileSystemName, $path)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->getTimestamp($path);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getSize($fileSystemName, $path)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->getSize($path);
    }

    /**
     * @param string $fileSystemName
     * @param string $dirname
     * @param array $config
     *
     * @return bool
     */
    public function createDir($fileSystemName, $dirname, array $config = [])
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->createDir($dirname, $config);
    }

    /**
     * @param string $fileSystemName
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($fileSystemName, $dirname)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->deleteDir($dirname);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param string $newpath
     *
     * @return string|false
     */
    public function copy($fileSystemName, $path, $newpath)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->copy($path, $newpath);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false
     */
    public function delete($fileSystemName, $path)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->delete($path);
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
     * @param string $content
     * @param array $config
     *
     * @return bool
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
     *
     * @return string|false
     */
    public function read($fileSystemName, $path)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->read($path);
    }

    /**
     * @param string $fileSystemName
     * @param string $newpath
     * @param string $path
     *
     * @return string|false
     */
    public function rename($fileSystemName, $path, $newpath)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->rename($path, $newpath);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool
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
     * @return bool
     */
    public function write($fileSystemName, $path, $content, array $config = [])
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->write($path, $content, $config);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function putStream($fileSystemName, $path, $resource, array $config = [])
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->putStream($path, $resource, $config);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return resource|false
     */
    public function readStream($fileSystemName, $path)
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->readStream($path);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function updateStream($fileSystemName, $path, $resource, array $config = [])
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->updateStream($path, $resource, $config);
    }

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function writeStream($fileSystemName, $path, $resource, array $config = [])
    {
        return $this->flysystemService
            ->getFilesystemByName($fileSystemName)
            ->writeStream($path, $resource, $config);
    }

}
