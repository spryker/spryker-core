<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model;

use League\Flysystem\AdapterInterface;
use Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface;
use Spryker\Shared\Flysystem\OperationHandler\WriteOperationHandlerTrait;

class Writer implements WriterInterface
{

    use WriteOperationHandlerTrait;

    /**
     * @var \Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface
     */
    protected $filesystemProvider;

    /**
     * @param \Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface $filesystemProvider
     */
    public function __construct(FilesystemProviderInterface $filesystemProvider)
    {
        $this->filesystemProvider = $filesystemProvider;
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return void
     */
    public function markAsPrivate($filesystemName, $path)
    {
        $this->handleWriteOperation(function () use ($filesystemName, $path) {
            $visibility = AdapterInterface::VISIBILITY_PRIVATE;

            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->setVisibility($path, $visibility);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return void
     */
    public function markAsPublic($filesystemName, $path)
    {
        $this->handleWriteOperation(function () use ($filesystemName, $path) {
            $visibility = AdapterInterface::VISIBILITY_PUBLIC;

            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->setVisibility($path, $visibility);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $dirname
     * @param array $config
     *
     * @return void
     */
    public function createDir($filesystemName, $dirname, array $config = [])
    {
        $this->handleWriteOperation(function () use ($filesystemName, $dirname, $config) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->createDir($dirname, $config);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $dirname
     *
     * @return void
     */
    public function deleteDir($filesystemName, $dirname)
    {
        $this->handleWriteOperation(function () use ($filesystemName, $dirname) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->deleteDir($dirname);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param string $newpath
     *
     * @return void
     */
    public function copy($filesystemName, $path, $newpath)
    {
        $this->handleWriteOperation(function () use ($filesystemName, $path, $newpath) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->copy($path, $newpath);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return void
     */
    public function delete($filesystemName, $path)
    {
        $this->handleWriteOperation(function () use ($filesystemName, $path) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->delete($path);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return void
     */
    public function put($filesystemName, $path, $content, array $config = [])
    {
        $this->handleWriteOperation(function () use ($filesystemName, $path, $content, $config) {
            $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->put($path, $content, $config);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param string $newpath
     *
     * @return void
     */
    public function rename($filesystemName, $path, $newpath)
    {
        $this->handleWriteOperation(function () use ($filesystemName, $path, $newpath) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->rename($path, $newpath);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return void
     */
    public function update($filesystemName, $path, $content, array $config = [])
    {
        $this->handleWriteOperation(function () use ($filesystemName, $path, $content, $config) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->update($path, $content, $config);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return void
     */
    public function write($filesystemName, $path, $content, array $config = [])
    {
        $this->handleWriteOperation(function () use ($filesystemName, $path, $content, $config) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->write($path, $content, $config);
        });
    }

}
