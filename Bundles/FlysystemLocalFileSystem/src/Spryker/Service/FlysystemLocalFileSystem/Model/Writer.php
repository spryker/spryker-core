<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemLocalFileSystem\Model;

use League\Flysystem\AdapterInterface;
use Spryker\Service\FlysystemLocalFileSystem\Model\Provider\FilesystemProviderInterface;

class Writer implements WriterInterface
{

    /**
     * @var \Spryker\Service\FlysystemLocalFileSystem\Model\Provider\FilesystemProviderInterface
     */
    protected $filesystemService;

    /**
     * @param \Spryker\Service\FlysystemLocalFileSystem\Model\Provider\FilesystemProviderInterface $filesystemProvider
     */
    public function __construct(FilesystemProviderInterface $filesystemProvider)
    {
        $this->filesystemService = $filesystemProvider;
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function markAsPrivate($filesystemName, $path)
    {
        $visibility = AdapterInterface::VISIBILITY_PRIVATE;

        return $this->filesystemService
            ->getFilesystemByName($filesystemName)
            ->setVisibility($path, $visibility);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function markAsPublic($filesystemName, $path)
    {
        $visibility = AdapterInterface::VISIBILITY_PUBLIC;

        return $this->filesystemService
            ->getFilesystemByName($filesystemName)
            ->setVisibility($path, $visibility);
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
        return $this->filesystemService
            ->getFilesystemByName($filesystemName)
            ->createDir($dirname, $config);
    }

    /**
     * @param string $filesystemName
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($filesystemName, $dirname)
    {
        return $this->filesystemService
            ->getFilesystemByName($filesystemName)
            ->deleteDir($dirname);
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
        return $this->filesystemService
            ->getFilesystemByName($filesystemName)
            ->copy($path, $newpath);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function delete($filesystemName, $path)
    {
        return $this->filesystemService
            ->getFilesystemByName($filesystemName)
            ->delete($path);
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
        return $this->filesystemService
            ->getFilesystemByName($filesystemName)
            ->put($path, $content, $config);
    }

    /**
     * @param string $filesystemName
     * @param string $newpath
     * @param string $path
     *
     * @return bool
     */
    public function rename($filesystemName, $path, $newpath)
    {
        return $this->filesystemService
            ->getFilesystemByName($filesystemName)
            ->rename($path, $newpath);
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
        return $this->filesystemService
            ->getFilesystemByName($filesystemName)
            ->update($path, $content, $config);
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
        return $this->filesystemService
            ->getFilesystemByName($filesystemName)
            ->write($path, $content, $config);
    }

}
