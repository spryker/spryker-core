<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Dependency\Service;

class FileSystemToFlysystemBridge implements FileSystemToFlysystemInterface
{

    /**
     * @var \Spryker\Service\Flysystem\FlysystemServiceInterface
     */
    protected $flysystemService;

    /**
     * @param \Spryker\Service\Flysystem\FlysystemServiceInterface $flysystemService
     */
    public function __construct($flysystemService)
    {
        $this->flysystemService = $flysystemService;
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function isPrivate($filesystemName, $path)
    {
        return $this->flysystemService->isPrivate($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceMetadataTransfer|null
     */
    public function getMetadata($filesystemName, $path)
    {
        return $this->flysystemService->getMetadata($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getMimeType($filesystemName, $path)
    {
        return $this->flysystemService->getMimeType($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getTimestamp($filesystemName, $path)
    {
        return $this->flysystemService->getTimestamp($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return int|false
     */
    public function getSize($filesystemName, $path)
    {
        return $this->flysystemService->getSize($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function has($filesystemName, $path)
    {
        return $this->flysystemService->has($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function read($filesystemName, $path)
    {
        return $this->flysystemService->read($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $directory
     * @param bool $recursive
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceTransfer[]
     */
    public function listContents($filesystemName, $directory = '', $recursive = false)
    {
        return $this->flysystemService->listContents($filesystemName, $directory, $recursive);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function markAsPrivate($filesystemName, $path)
    {
        return $this->flysystemService->markAsPrivate($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function markAsPublic($filesystemName, $path)
    {
        return $this->flysystemService->markAsPublic($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $dirname
     * @param array $config
     *
     * @return bool
     */
    public function createDir($filesystemName, $dirname, array $config = [])
    {
        return $this->flysystemService->createDir($filesystemName, $dirname, $config);
    }

    /**
     * @param string $filesystemName
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($filesystemName, $dirname)
    {
        return $this->flysystemService->deleteDir($filesystemName, $dirname);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy($filesystemName, $path, $newpath)
    {
        return $this->flysystemService->copy($filesystemName, $path, $newpath);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function delete($filesystemName, $path)
    {
        return $this->flysystemService->delete($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool
     */
    public function put($filesystemName, $path, $content, array $config = [])
    {
        return $this->flysystemService->put($filesystemName, $path, $content, $config);
    }

    /**
     * @param string $filesystemName
     * @param string $newpath
     * @param string $path
     *
     * @return string|false
     */
    public function rename($filesystemName, $path, $newpath)
    {
        return $this->flysystemService->rename($filesystemName, $path, $newpath);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool
     */
    public function update($filesystemName, $path, $content, array $config = [])
    {
        return $this->flysystemService->update($filesystemName, $path, $content, $config);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool
     */
    public function write($filesystemName, $path, $content, array $config = [])
    {
        return $this->flysystemService->write($filesystemName, $path, $content, $config);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function putStream($filesystemName, $path, $resource, array $config = [])
    {
        return $this->flysystemService->putStream($filesystemName, $path, $resource, $config);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return resource|false
     */
    public function readStream($filesystemName, $path)
    {
        return $this->flysystemService->readStream($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function updateStream($filesystemName, $path, $resource, array $config = [])
    {
        return $this->flysystemService->updateStream($filesystemName, $path, $resource, $config);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function writeStream($filesystemName, $path, $resource, array $config = [])
    {
        return $this->flysystemService->writeStream($filesystemName, $path, $resource, $config);
    }

}
